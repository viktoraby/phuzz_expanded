<?php

##########################################################################################
#                                      NoSQL Injection                                   #
##########################################################################################

uopz_set_return(
    'MongoDB\Driver\Manager',
    'executeQuery',
    function ($namespace, $query, $options = []) {
        try {
            $result = $this->executeQuery($namespace, $query);
            $the_exception = false;
        } catch(Throwable $e) {
            $result = false;
            $the_exception = $e;
        }

        if ($result === false) {
            $json = json_encode(
                [
                    'function' => 'MongoDB\Driver\Manager::executeQuery',
                    'params' => [$namespace, $query],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__NOSQLI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__NOSQLI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);

            if ($the_exception != null) {
                throw $the_exception;
            }
        }

        return $result;
    },
    true
);
?>