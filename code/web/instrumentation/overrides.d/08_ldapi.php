<?php

##########################################################################################
#                                     LDAP Injection                                     #
##########################################################################################

uopz_set_return(
    'ldap_search',
    function ($ldap, $base_dn, $filter, $attributes = [], $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER) {
        try {
            $result = ldap_search($ldap, $base_dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }
        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'ldap_search',
                    'params' => [$ldap, $base_dn, $filter],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);
            if($the_exception != null) {
                throw $the_exception;
            }
        }
        return $result;
    },
    true
);

uopz_set_return(
    'ldap_list',
    function ($ldap, $base_dn, $filter, $attributes = [], $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER) {
        try {
            $result = ldap_list($ldap, $base_dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }
        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'ldap_list',
                    'params' => [$ldap, $base_dn, $filter],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);
            if($the_exception != null) {
                throw $the_exception;
            }
        }
        return $result;
    },
    true
);

uopz_set_return(
    'ldap_read',
    function ($ldap, $base_dn, $filter, $attributes = [], $attrsonly = 0, $sizelimit = 0, $timelimit = 0, $deref = LDAP_DEREF_NEVER) {
        try {
            $result = ldap_read($ldap, $base_dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }
        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'ldap_read',
                    'params' => [$ldap, $base_dn, $filter],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__LDAPI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);
            if($the_exception != null) {
                throw $the_exception;
            }
        }
        return $result;
    },
    true
);

?>