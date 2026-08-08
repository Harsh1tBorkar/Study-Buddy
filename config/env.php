<?php
// config/env.php

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    // Parse the .env file as if it were an INI file
    $envArray = parse_ini_file($envPath);
    
    foreach ($envArray as $key => $value) {
        // Load the variables into PHP's superglobal environment arrays
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
} else {
    die("Critical Error: The .env configuration file is missing.");
}
?>