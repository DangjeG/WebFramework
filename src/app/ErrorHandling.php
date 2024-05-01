<?php

namespace Dangje\WebFramework;

function errorHandler($errno, $errstr, $errfile, $errline)
{
    $errorCodes = [
        E_ERROR => 'Fatal error',
        E_WARNING => 'Warning',
        E_NOTICE => 'Notice',
        E_USER_ERROR => 'User error',
        E_USER_WARNING => 'User warning',
        E_USER_NOTICE => 'User notice'
    ];

    if (!array_key_exists($errno, $errorCodes)) {
        $errorCodes[$errno] = 'Unknown error';
    }

    $error = [
        'code' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];

    // Log the error

    // Send the error to the user
}

set_error_handler('Dangje\WebFramework\errorHandler');