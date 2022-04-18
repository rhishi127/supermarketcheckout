<?php

return [
    // Retrieve list of modules used in this application.
    'config_glob_paths' => [
        'global' =>realpath(__DIR__) . '/autoload/global.php',
        'local' => realpath(__DIR__) . '/autoload/local.php'
    ],
];