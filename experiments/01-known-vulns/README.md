# Fuzzing of Known Vulnerabilities

This experiment evaluates PHUZZ_EXPANDED against established vulnerability scanners using a set of known vulnerabilities in intentionally vulnerable web applications.

The evaluation compares the following scanners:

- PHUZZ_EXPANDED
- BurpSuite Professional
- OWASP ZAP
- Wapiti

## Test Applications and Vulnerabilities

We evaluate 16 known vulnerability cases across four applications or test environments:

- bWAPP
- NoSQL
- Testsuite
- XVWA

The tested vulnerability types include:

- HTTP Header Injection
- LDAP Injection
- SSRF (Server-Side Request Forgery)
- NoSQL Injection
- SSTI (Server-Side Template Injection)

The complete test cases and scanner results are documented in `known-vulns.csv`.

## Experiment Configuration

For each vulnerability, configuration files are maintained in `../../code/fuzzer/configs/`. These configurations initialize the fuzzers with the vulnerable endpoint and the required parameters. Vulnerable parameter values are replaced with the value `fuzz`.

BurpSuite Professional, OWASP ZAP, and Wapiti were tested manually against the same vulnerability cases.

## Results

The file `known-vulns.csv` contains the tested vulnerability cases and the corresponding detection results for all four scanners.

## Auxiliary Scripts

The `scripts/` directory contains two optional Python scripts for collecting and classifying known WordPress plugin vulnerabilities:

- `01-scrape-wpvulndb.py`: Retrieves vulnerability information from WPScan.
- `02-analyze_vulns.py`: Filters and classifies the collected vulnerabilities into predefined categories.

These scripts are auxiliary research tools and are not required to execute the vulnerability tests documented in `known-vulns.csv`.