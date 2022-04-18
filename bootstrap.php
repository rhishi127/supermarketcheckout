<?php

    require_once dirname(__FILE__) .'/vendor/autoload.php';

    $appConfig = require __DIR__ . '/config/application.config.php';

/*    error_reporting(self::$discount_temp_array);
    ini_set('display_errors', 1);*/

use Custom\ApplicationMain;

try {
    if (!class_exists(ApplicationMain::class)) {
        throw new RuntimeException(
            "unable to load application"
        );
    }
    } catch (RuntimeException $e) {
        echo $e->getMessage();
        exit();
    }
    ApplicationMain::init($appConfig);


