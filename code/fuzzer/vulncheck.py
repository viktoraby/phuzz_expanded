import html
import os
import re

import bleach
import esprima
import urllib.parse as urlparse
import json
from bs4 import BeautifulSoup, element
from difflib import SequenceMatcher
from utils import fuzz_open

import requests

class VulnCheck():
    NAME = "Example"

    def check(self, candidate):
        return False



# Code for this class taken from https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/
class WebFuzzXSSVulnCheck(VulnCheck):
    NAME = "WebFuzzXSSVulnCheck"
    CONFIDENCE_NONE = 0
    CONFIDENCE_LOW = 1
    CONFIDENCE_HIGH = 2

    URLATTRIBUTES = [
        "action",
        "cite",
        "data",
        "formaction",
        "href",
        "longdesc",
        "manifest",
        "poster",
        "src"
    ]

    FLAGGED_ELEMENTS = {
        CONFIDENCE_NONE: {},
        CONFIDENCE_LOW: {},
        CONFIDENCE_HIGH: {},
    }


    def _webfuzz_misc_longest_str_match(self, haystack, needle):
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/misc.py#L101
        match = SequenceMatcher(None, haystack, needle)
        (_,__,size) = match.find_longest_match(0, len(haystack), 0, len(needle))
        return size


    def _webfuzz_xss_precheck(self, candidate):
        # Taken from WebFuzz
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/detector.py#L149

        raw_html = candidate.response.text
        if self._webfuzz_misc_longest_str_match(raw_html, "0xdeadbeef") >= 5:
            return True
        else:
            return False

    def _webfuzz_xss_record_response(self, candidate, confidence, id_, elem_type, value):

        if confidence == WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
            return

        candidate_url = candidate.response.url

        if candidate_url not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence]:
            WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url] = set()

        if id_ not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url]:

            if not WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[WebFuzzXSSVulnCheck.CONFIDENCE_HIGH].get(candidate_url, []):
                if confidence == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    self.xss_count += 1

            WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[confidence][candidate_url].add(id_)

            # if node.is_mutated:
            #     # reward parent node with a sink found
            #     node.parent_request.has_sinks = True


    def _webfuzz_xss_should_analyze(self, id_, url, elem_content):
        if id_ not in WebFuzzXSSVulnCheck.FLAGGED_ELEMENTS[WebFuzzXSSVulnCheck.CONFIDENCE_HIGH].get(url, []) and \
            self._webfuzz_misc_longest_str_match(elem_content, "0xdeadbeef") >= 5:
            return True
        else:
            return False

    def _webfuzz_xss_js_ast_traversal(self, node):
        confidence = WebFuzzXSSVulnCheck.CONFIDENCE_NONE

        if type(node) == list:
            for stmt in node:
                res = self._webfuzz_xss_js_ast_traversal(stmt)
                if res == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        elif 'esprima.nodes.CallExpression' in str(type(node)):
             if node.callee.name in ["alert", "prompt", "confirm"]:

                res = self._webfuzz_xss_js_ast_traversal(node.arguments)
                if res > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
                    # 0xdeadbeef found in one of its arguments
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        elif 'esprima.nodes.TaggedTemplateExpression' in str(type(node)):
            if node.quasi.type == 'TemplateLiteral' and \
               node.tag.name in ["alert", "prompt", "confirm"]:

                res = self._webfuzz_xss_js_ast_traversal(node.quasi.quasis)
                if res > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
                    # 0xdeadbeef found in one of its arguments
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        if "esprima.nodes" in str(type(node)):
            for attr in dir(node):
                res = self._webfuzz_xss_js_ast_traversal(getattr(node, attr))
                if res == WebFuzzXSSVulnCheck.CONFIDENCE_HIGH:
                    return WebFuzzXSSVulnCheck.CONFIDENCE_HIGH
                else:
                    confidence = max(res, confidence)

        if type(node) == str:
            if longest_str_match(node, "0xdeadbeef") >= 5:
                confidence = max(WebFuzzXSSVulnCheck.CONFIDENCE_LOW, confidence)

        return confidence

    def _webfuzz_xss_handle_script(self, elem_content):
        try:
            script = esprima.parseScript(elem_content)
            return self._webfuzz_xss_js_ast_traversal(script.body)
        except:
            # fallback to weak method
            if self._webfuzz_misc_longest_str_match(elem_content, "0xdeadbeef") >= 5:
                return WebFuzzXSSVulnCheck.CONFIDENCE_LOW
            
            return WebFuzzXSSVulnCheck.CONFIDENCE_NONE

    def _webfuzz_xss_handle_attr(self, attr_name, attr_content):
        result = WebFuzzXSSVulnCheck.CONFIDENCE_LOW

        if attr_name.lower() in WebFuzzXSSVulnCheck.URLATTRIBUTES and \
           attr_content[:11].lower() == "javascript:":
            # strip leading javascript
            attr_content = attr_content[11:]
            result = self._webfuzz_xss_handle_script(attr_content)

        elif attr_name[:2] == "on":
            result = self._webfuzz_xss_handle_script(attr_content)

        return result

    def _webfuzz_xss_scanner(self, candidate):
        # Taken from Webfuzz
        # https://github.com/ovanr/webFuzz/blob/4e8da2aa80f932cc0f7c05212620b24654a3092c/webFuzz/webFuzz/detector.py#L154

        raw_html = candidate.response.text
        bsoup = BeautifulSoup(raw_html, "html.parser")
        confidence = WebFuzzXSSVulnCheck.CONFIDENCE_NONE

        for elem in bsoup.find_all():
            if type(elem) != element.Tag:
                continue

            id_ = elem.name + "/" + elem.attrs.get('id', "")

            if elem.name == "script":
                if not self._webfuzz_xss_should_analyze(id_, candidate.response.url, elem.text):
                    continue

                result = self._webfuzz_xss_handle_script(elem.text)

                self._webfuzz_xss_record_response(candidate, result, id_, elem_type="Script", value=elem.text)

                confidence = max(result, confidence)

            for (attr_name, attr_value) in elem.attrs.items():
                param_id = id_ + "/" + attr_name
                if not self._webfuzz_xss_should_analyze(param_id, candidate.response.url, attr_value):
                    continue

                result = self._webfuzz_xss_handle_attr(attr_name, attr_value)
                self._webfuzz_xss_record_response(candidate, result, param_id, elem_type=f"Attribute {attr_name}", value=attr_value)

                confidence = max(result, confidence)

        return confidence

    def check(self, candidate):
        if not candidate.response:
            return False

        # Taken from WebFuzz
        # https://github.com/ovanr/webFuzz/blob/v1.2.0/webFuzz/webFuzz/worker.py#L126
        if not self._webfuzz_xss_precheck(candidate):
            return False

        if self._webfuzz_xss_scanner(candidate) > WebFuzzXSSVulnCheck.CONFIDENCE_NONE:
            return True

        return False


class XSSVulnCheck(VulnCheck):
    NAME = "XSS"

    def check(self, candidate):
        if not candidate.response:
            return False
        for param_type in ['query_params', 'body_params', 'headers', 'cookies']:
            for param in candidate.fuzz_params[param_type].items():
                if html.unescape(bleach.clean(param[1], strip=True)) != param[1]:
                    if candidate.response.text.find(param[1]) != -1:
                        candidate.vulns.append(self.NAME)
                        return True
        return False


class SQLiVulnCheck(VulnCheck):
    NAME = "SQLi"

    def __init__(self, mysql_errors_folder):
        self.mysql_errors_folder = mysql_errors_folder

    def check(self, candidate):
        sqli_file = os.path.join(
            self.mysql_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(sqli_file):
            return True
        return False

class ParamBasedSQLiVulnCheck(VulnCheck):
    NAME = "SQLi"

    def __init__(self, mysql_errors_folder):
        self.mysql_errors_folder = mysql_errors_folder

    def check(self, candidate):
        sqli_file = os.path.join(
            self.mysql_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(sqli_file):
            return False

        for line in fuzz_open(sqli_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False


class CommandInjectionVulnCheck(VulnCheck):
    NAME = "CommandInjection"

    def __init__(self, shell_errors_folder):
        self.shell_errors_folder = shell_errors_folder

    def check(self, candidate):
        cmd_injection_file = os.path.join(
            self.shell_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(cmd_injection_file):
            return True
        return False

class ParamBasedCommandInjectionVulnCheck(VulnCheck):
    NAME = "CommandInjection"

    def __init__(self, shell_errors_folder):
        self.shell_errors_folder = shell_errors_folder

    def check(self, candidate):
        cmd_injection_file = os.path.join(
            self.shell_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(cmd_injection_file):
            return False

        for line in fuzz_open(cmd_injection_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class UnserializeVulnCheck(VulnCheck):
    NAME = "Unserialize"

    def __init__(self, unserialize_errors_folder):
        self.unserialize_errors_folder = unserialize_errors_folder

    def check(self, candidate):
        unserialize_file = os.path.join(
            self.unserialize_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(unserialize_file):
            return True
        return False

class ParamBasedUnserializeVulnCheck(VulnCheck):
    NAME = "Unserialize"

    def __init__(self, unserialize_errors_folder):
        self.unserialize_errors_folder = unserialize_errors_folder

    def check(self, candidate):
        unserialize_file = os.path.join(
            self.unserialize_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(unserialize_file):
            return False

        for line in fuzz_open(unserialize_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class PathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(pathtraversal_file):
            return True
        return False

class ParamBasedPathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(pathtraversal_file):
            return False

        for line in fuzz_open(pathtraversal_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class WebPathBasedPathTraversalVulnCheck(VulnCheck):
    NAME = "PathTraversal"

    def __init__(self, pathtraversal_errors_folder):
        self.pathtraversal_errors_folder = pathtraversal_errors_folder
        self.web_paths = []
        with open(os.path.join("/shared-tmpfs", "web-paths.txt")) as f:
            for path in f:
                self.web_paths.append(path.strip())

    def check(self, candidate):
        pathtraversal_file = os.path.join(
            self.pathtraversal_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(pathtraversal_file):
            return False

        for line in fuzz_open(pathtraversal_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                if error_param in self.web_paths:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class OpenRedirectVulnCheck(VulnCheck):
    NAME = "OpenRedirect"

    def check(self, candidate):
        if candidate.response is None:
            return False

        respones = candidate.response.history if candidate.response.history else [candidate.response]
        for resp in respones:
            if 300 <= resp.status_code < 400 and 'Location' in resp.headers:
                dest_url = resp.headers['Location']
                dest_url_parts = list(urlparse.urlparse(dest_url))
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        pval_parts = list(urlparse.urlparse(pval))
                        if pval == dest_url or pval in dest_url_parts or dest_url_parts == pval_parts:
                            return True
                            # This could be more sophisticated, but should be sufficient for easy open redirects.
        
        return False

class XXEVulnCheck(VulnCheck):
    NAME = "XXE"

    def __init__(self, xxe_errors_folder):
        self.xxe_errors_folder = xxe_errors_folder

    def check(self, candidate):
        xxe_file = os.path.join(
            self.xxe_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(xxe_file):
            return True
        return False

class ParamBasedXXEVulnCheck(VulnCheck):
    NAME = "XXE"

    def __init__(self, xxe_errors_folder):
        self.xxe_errors_folder = xxe_errors_folder

    def check(self, candidate):
        xxe_file = os.path.join(
            self.xxe_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(xxe_file):
            return False

        for line in fuzz_open(xxe_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False


class LDAPVulnCheck(VulnCheck):
    NAME = "LDAP Injection"

    def __init__(self, ldapi_errors_folder):
        self.ldapi_errors_folder = ldapi_errors_folder
    
    def check(self, candidate):
        ldap_injection_file = os.path.join(
            self.ldapi_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(ldap_injection_file):
            return True
        return False
    

class ParamBasedLDAPVulnCheck(VulnCheck):
    NAME = "LDAP Injection"

    def __init__(self, ldapi_errors_folder):
        self.ldapi_errors_folder = ldapi_errors_folder

    def check(self, candidate):
        ldap_injection_file = os.path.join(
            self.ldapi_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(ldap_injection_file):
            return False
        
        for line in fuzz_open(ldap_injection_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False
    
class SSTIVulnCheck(VulnCheck):
    NAME = "SSTI"

    def __init__(self, ssti_errors_folder):
        self.ssti_errors_folder = ssti_errors_folder

    def check(self, candidate):
        ssti_file = os.path.join(
            self.ssti_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(ssti_file):
            return True
        return False
    
class BrokenParamBasedSSTIVulnCheck(VulnCheck):
    NAME = "SSTI"

    def __init__(self, ssti_errors_folder):
        self.ssti_errors_folder = ssti_errors_folder

    def check(self, candidate):
        ssti_file = os.path.join(
            self.ssti_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(ssti_file):
            return False

        for line in fuzz_open(ssti_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class ParamBasedSSTIVulnCheck(VulnCheck):
    NAME = "SSTI"

    def __init__(self, ssti_errors_folder):
        self.ssti_errors_folder = ssti_errors_folder

    def check(self, candidate):
        # Unterstützte SSTI-Payloads
        payloads = ["{{99901 * 99991}}", "#{99901*99991}", "{{99901*'99991'}}"]
        rendered_value = "9989200891"

        # Durchsuche alle Fuzz-Parameter in der Anfrage
        for param_type in candidate.fuzz_params:
            for param, value in candidate.fuzz_params[param_type].items():
                # Prüfen, ob einer der Payloads in einem Parameter vorkommt
                if any(payload in value for payload in payloads):
                    # Wenn die Antwort existiert und das erwartete Ergebnis enthält
                    if candidate.response and rendered_value in candidate.response.text:
                        return True

        # Keine Übereinstimmung gefunden
        return False
    
class HTTPHeaderInjectionVulnCheck(VulnCheck):
    NAME = "HTTPHeaderInjection"

    def __init__(self, httphi_errors_folder):
        self.httphi_errors_folder = httphi_errors_folder

    def check(self, candidate):
        http_header_injection_file = os.path.join(
            self.httphi_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(http_header_injection_file):
            return True
        return False

class ParamBasedHTTPHeaderInjectionVulnCheck(VulnCheck):
    NAME = "HTTPHeaderInjection"

    def __init__(self, httphi_errors_folder):
        self.httphi_errors_folder = httphi_errors_folder

    def check(self, candidate):
        http_header_injection_file = os.path.join(
            self.httphi_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(http_header_injection_file):
            return False

        for line in fuzz_open(http_header_injection_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class SSRFVulnCheck(VulnCheck):
    NAME = "SSRF"

    def __init__(self, ssrf_errors_folder):
        self.ssrf_errors_folder = ssrf_errors_folder

    def check(self, candidate):
        ssrf_file = os.path.join(
            self.ssrf_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(ssrf_file):
            return True
        return False

class ImageSSRFVulnCheck(VulnCheck):
    NAME = "SSRF"

    def __init__(self, ssrf_errors_folder):
        self.ssrf_errors_folder = ssrf_errors_folder

    def check(self, candidate):

        # Bild wird geladen
        test_payload = "https://raw.githubusercontent.com/gehaxelt/phuzz/refs/heads/main/code/web/applications/bwapp/images/evil_bee.png"

        # Originalbild laden (zum Vergleich)
        try:
            evil_bee_data = requests.get(test_payload, timeout=5).content
        except Exception:
            evil_bee_data = None

        # === Phase 1: Prüfen, ob der Payload gesendet wurde ===
        payload_sent = False

        # Durchsuche alle Fuzzing-Parameter (headers, cookies, query_params, body_params)
        for param_type, params in candidate.fuzz_params.items():
            for key, value in params.items():
                if isinstance(value, str) and test_payload in value:
                    payload_sent = True
                    break
            if payload_sent:
                break

        if not payload_sent:
            # Kein Anzeichen für SSRF-Versuch
            return False

        # === Phase 2: Prüfen, ob das Bild (oder ähnlicher Inhalt) in der Response vorkommt ===
        if not candidate.response or not evil_bee_data:
            return False

        try:
            response_bytes = candidate.response.content

            # Schneller Check: Enthält der Response den Payload-Link selbst?
            if test_payload in candidate.response.text:
                return True

            # Toleranzvergleich
            similarity = SequenceMatcher(
                None,
                evil_bee_data,
                response_bytes
            ).ratio()

            # Wenn die Ähnlichkeit groß genug ist, gilt SSRF als erkannt
            if similarity > 0.7:
                return True

        except Exception:
            # Falls Response fehlerhaft oder unlesbar ist
            pass

        return False

class ParamBasedSSRFVulnCheck(VulnCheck):
    NAME = "SSRF"

    def __init__(self, ssrf_errors_folder):
        self.ssrf_errors_folder = ssrf_errors_folder

    def check(self, candidate):
        ssrf_file = os.path.join(
            self.ssrf_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(ssrf_file):
            return False

        for line in fuzz_open(ssrf_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False

class NoSQLiVulnCheck(VulnCheck):
    NAME = "NoSQLi"

    def __init__(self, nosqli_errors_folder):
        self.nosqli_errors_folder = nosqli_errors_folder

    def check(self, candidate):
        nosqli_file = os.path.join(
            self.nosqli_errors_folder, f"{candidate.coverage_id}.json"
        )
        if os.path.isfile(nosqli_file):
            return True
        return False

class ParamBasedNoSQLInjectionVulnCheck(VulnCheck):
    NAME = "NoSQLi"

    def __init__(self, nosqli_errors_folder):
        self.nosqli_errors_folder = nosqli_errors_folder

    def check(self, candidate):
        # Prüfen, ob der Parameter {"$ne": null} in den Fuzz-Parametern enthalten ist
        for vuln_type in candidate.fuzz_params.keys():
            for pkey, pval in candidate.fuzz_params[vuln_type].items():
                if pval == '{"$ne":null}' or pval == "cats" or pval == "%7B%22%24ne%22%3Anull%7D":
                   if candidate.response:
                        # Debugging: Ausgabe der gesamten Antwort für eine genauere Prüfung
                        
                        # 1. Prüfen, ob der Fehlercode im Text enthalten ist
                        if "unknown top level operator: $ne" in candidate.response.text:
                            print("Fehlercode gefunden im Text!")  # Debugging
                            return True
                        
                        # 2. Falls die Antwort im HTML-Format vorliegt, durchsuchen wir die HTML-Struktur nach dem Fehler
                        if "<html>" in candidate.response.text.lower():  # Falls es sich um HTML handelt
                            if "unknown top level operator: $ne" in candidate.response.text:
                                print("Fehlercode in HTML gefunden!")  # Debugging
                                return True
                        
                        # 3. Versuch, Fehler im Header zu finden, falls PHP Fehler in den Headern ausgibt
                        if "X-PHP-Error" in candidate.response.headers:
                            error_message = candidate.response.headers["X-PHP-Error"]
                            if "unknown top level operator: $ne" in error_message:
                                print("Fehlercode im PHP-Header gefunden!")  # Debugging
                                return True
                        
                        # 4. Versuch, Antwort als JSON zu parsen (falls PHP Fehler in einem strukturierten Format gibt)
                        try:
                            response_json = candidate.response.json()
                            if "error" in response_json and "message" in response_json:
                                if "unknown top level operator: $ne" in response_json["message"]:
                                    print("Fehlercode im JSON gefunden!")  # Debugging
                                    return True
                        except ValueError:
                            pass  # Falls es kein gültiges JSON ist
        return False
    
class BrokenParamBasedNoSQLiVulnCheck(VulnCheck):
    NAME = "NoSQLi"

    def __init__(self, nosqli_errors_folder):
        self.nosqli_errors_folder = nosqli_errors_folder

    def check(self, candidate):
        nosql_injection_file = os.path.join(
            self.nosqli_errors_folder, f"{candidate.coverage_id}.json"
        )
        if not os.path.isfile(nosql_injection_file):
            return False

        for line in fuzz_open(nosql_injection_file):
            if not line.strip():
                continue
            error = json.loads(line)
            for error_param in error['params']:
                if not error_param:
                    continue
                for vuln_type in candidate.fuzz_params.keys():
                    for pkey, pval in candidate.fuzz_params[vuln_type].items():
                        if pval in error_param:
                            return True
        return False
            
class VulnChecker():
    def __init__(self):
        self.vuln_checkers = []

    def vuln_check(self, candidate):
        pass


class DefaultVulnChecker(VulnChecker):
    def __init__(self, mysql_errors_folder=None, shell_errors_folder=None, unserialize_errors_folder=None, pathtraversal_errors_folder=None, xxe_errors_folder=None, ldapi_errors_folder=None, ssti_errors_folder=None, httphi_errors_folder=None, ssrf_errors_folder=None, nosqli_errors_folder=None):
        super(VulnChecker, self).__init__()
        self.vuln_checkers = [
            WebFuzzXSSVulnCheck(),
            SQLiVulnCheck(mysql_errors_folder),
            CommandInjectionVulnCheck(shell_errors_folder),
            UnserializeVulnCheck(unserialize_errors_folder),
            PathTraversalVulnCheck(pathtraversal_errors_folder),
            OpenRedirectVulnCheck(),
            XXEVulnCheck(xxe_errors_folder),
            LDAPVulnCheck(ldapi_errors_folder),
            SSTIVulnCheck(ssti_errors_folder),
            HTTPHeaderInjectionVulnCheck(httphi_errors_folder),
            SSRFVulnCheck(ssrf_errors_folder),
            NoSQLiVulnCheck(nosqli_errors_folder)
        ]

    def vuln_check(self, candidate):
        vulns = []
        for vuln_check in self.vuln_checkers:
            if vuln_check.check(candidate):
                vulns.append(vuln_check.NAME)
        return vulns

class ParamBasedVulnChecker(DefaultVulnChecker):
    def __init__(self, mysql_errors_folder=None, shell_errors_folder=None, unserialize_errors_folder=None, pathtraversal_errors_folder=None, xxe_errors_folder=None, ldapi_errors_folder=None, ssti_errors_folder=None, httphi_errors_folder=None, ssrf_errors_folder=None, nosqli_errors_folder=None):
        self.vuln_checkers = [
            WebFuzzXSSVulnCheck(),
            ParamBasedSQLiVulnCheck(mysql_errors_folder),
            ParamBasedCommandInjectionVulnCheck(shell_errors_folder),
            ParamBasedUnserializeVulnCheck(unserialize_errors_folder),
            ParamBasedPathTraversalVulnCheck(pathtraversal_errors_folder), # This one was used during the main analysis -> it discovered 'fu' in 'functions.php' (Wordpress), which is a false positive. 
            WebPathBasedPathTraversalVulnCheck(pathtraversal_errors_folder), # This one ignores existing files, such as functions.php, and should thus report less false positives.
            OpenRedirectVulnCheck(),
            ParamBasedXXEVulnCheck(xxe_errors_folder),
            ParamBasedLDAPVulnCheck(ldapi_errors_folder),
            ParamBasedSSTIVulnCheck(ssti_errors_folder),
            ParamBasedHTTPHeaderInjectionVulnCheck(httphi_errors_folder),
            ParamBasedSSRFVulnCheck(ssrf_errors_folder),
            NoSQLiVulnCheck(nosqli_errors_folder),
            ImageSSRFVulnCheck(ssrf_errors_folder)
        ]