<?php


spl_autoload_register(function ($className) {
    $fileName = str_replace('\\', '/', $className) . '.php';
    if (file_exists($fileName)) {
        require_once $fileName;
        return true;
    }
    return false;
});