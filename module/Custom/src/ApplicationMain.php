<?php

namespace Custom;

use Custom\Connections\DBConnectionAdapter;

class ApplicationMain
{
    /**
     *
     * @var array<array> $project_conf
     */
    public static $project_conf = [];

    /**
     *
     * @param array $configuration
     * @param bool $connectionOnly
     */
    public static function init(array $configuration = [], bool $connectionOnly = false)
    {
        ini_set('memory_limit', '512M');
        /* ini_set('xdebug.max_nesting_level', 3000);
         ini_set('xdebug.var_display_max_children', -1);
         ini_set('xdebug.var_display_max_data', -1);
         ini_set('xdebug.var_display_max_depth', -1);*/
        $protocol = $_SERVER['REQUEST_SCHEME'].'://';
        $host = $_SERVER['HTTP_HOST'] . '/';
        $project = explode('/', $_SERVER['REQUEST_URI'])[1];
        $baseUrl = $protocol . $host . $project;
        $_SESSION['base_url'] = $baseUrl;
        $connConfig = $configuration['config_glob_paths'];
        self::setConnectionConfig($connConfig);
    }

    private static function setConnectionConfig($connConfig)
    {
        $globalConfig = file_exists($connConfig['global']) ? require_once $connConfig['global'] : [] ;
        $localConfig = file_exists($connConfig['local']) ? require_once $connConfig['local'] : [] ;
        $result = array_replace($globalConfig, $localConfig);
        DBConnectionAdapter::getInstance($result)->getConnection();
    }
}
