<?php

##########################################################################################
#                                          SSTI                                          #
##########################################################################################

uopz_set_return(
    'Twig\Environment::render',
    function ($name) {
        try {
            // Versuche, die originale render Methode aufzurufen
            $result = $this->render($name);
            $the_exception = false;
        } catch(Throwable $e) {
            // Im Falle eines Fehlers fangen wir die Ausnahme
            $result = false;
            $the_exception = $e;
        }

        // Wenn ein Fehler auftritt und wir eine Exception haben
        if ($result === false) {
            // Error Log in einer JSON-Datei speichern
            $json = json_encode(
                [
                    'function' => 'Twig\Environment::render',
                    'params' => [$name],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__SSTI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__SSTI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);

            // Falls eine Exception geworfen wurde, erneut werfen
            if ($the_exception != null) {
                throw $the_exception;
            }
        }

        return $result;
    },
    true
);

uopz_set_return(
    'Twig\Environment::load',
    function ($name) {
        try {
            // Versuche, die originale render Methode aufzurufen
            $result = $this->load($name);
            $the_exception = false;
        } catch(Throwable $e) {
            // Im Falle eines Fehlers fangen wir die Ausnahme
            $result = false;
            $the_exception = $e;
        }

        // Wenn ein Fehler auftritt und wir eine Exception haben
        if ($result === false) {
            // Error Log in einer JSON-Datei speichern
            $json = json_encode(
                [
                    'function' => 'Twig\Environment::load',
                    'params' => [$name],
                    'error' => $the_exception ? $e->getMessage() : 'unknown'
                ]
            );
            __fuzzer_file_put_contents(__FUZZER__SSTI_ERRORS_PATH . __FUZZER__COVID . ".json", $json . "\n", FILE_APPEND);
            chmod(__FUZZER__SSTI_ERRORS_PATH . __FUZZER__COVID . ".json", 0777);

            // Falls eine Exception geworfen wurde, erneut werfen
            if ($the_exception != null) {
                throw $the_exception;
            }
        }

        return $result;
    },
    true
);

?>
