<?php

namespace Dangje\WebFramework;

class ErrorHandler {

    private $errorFormat = "<b>Error</b>. <b>Number</b>: %d. <b>String</b>: %s. <b>File</b>: %s. <b>Line</b>: %d";
    private $fatalErrorFormat = "<b>Fatal Error</b>. <b>Number</b>: %d. <b>String</b>: %s. <b>File</b>: %s. <b>Line</b>: %d";
    private $exceptionFormat = "<b>Exception</b>. <b>Number</b>: %d. <b>String</b>: %s. <b>File</b>: %s. <b>Line</b>: %d";

    public function register() {
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function fatalErrorHandler() {
        if (!empty($error = error_get_last()) AND $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            ob_get_clean();
            $this->showError($error['type'], $error['message'], $error['file'], $error['line'], $this->fatalErrorFormat);
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        
        $this->showError($errno, $errstr, $errfile, $errline, $this->errorFormat);

        return true;
    }

    public function exceptionHandler(\Exception $exception) {
        $this->showError(get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $this->exceptionFormat);

        return true;
    }

    protected function showError($errno, $errstr, $errfile, $errline, $errorFormat, $status = 500) {

        header("HTTP/1.1 {$status}");

        echo sprintf($errorFormat, $errno, $errstr, $errfile, $errline); 
    }
}
