<?php
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    spl_autoload_register(function($className) {
        if (strpos($className, 'Phactory\\') === 0) {
            require_once __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
        }
    });
}
