<?php
##########################################################################################
#                                  HTTP Header Injection                                 #
##########################################################################################

uopz_set_return(
    'header',
    function ($string) {
        $httphi = false;
        try{
            $result = header($string);
            $the_exception = null;
        }catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }

        if (preg_match('/[\0\r\n]/', $string)) {
            if($the_exception) {
                $errno = -1;
                $errstr = $the_exception->getMessage();
            } else {
                $errno = 'undefined';
                $errstr = 'Unknown error during header setting';
            }
            $json = json_encode(
                [
                    'function' => 'header',
                    'params' => [$string],
                    'errno' => $errno,
                    'errstr' => $errstr,
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);
            if($the_exception != null) {
                throw $e;
            }
        }
        return $result;
    },
    true
);

uopz_set_return(
    'setcookie',
    function ($name, $value = "", $expires = 0, $path = "", $domain = "", $secure = false, $httponly = false) {
        $httphi = false;
        try {
            $result = setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }

        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'setcookie',
                    'params' => [$name],
                    'error' => $the_exception ? $the_exception->getMessage() : 'unknown'
                ]
            );

            __fuzzer_file_put_contents(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);

            if ($the_exception != null) {
                throw $the_exception;
            }
        }

        return $result;
    },
    true
);

uopz_set_return(
    'http_response_code',
    function ($code) {
        try {
            $result = http_response_code($code);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }

        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'http_response_code',
                    'params' => [$code],
                    'error' => $the_exception ? $the_exception->getMessage() : 'unknown'
                ]
            );

            __fuzzer_file_put_contents(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__HTTPHI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);

            if ($the_exception != null) {
                throw $the_exception;
            }
        }

        return $result;
    },
    true
);
?>
