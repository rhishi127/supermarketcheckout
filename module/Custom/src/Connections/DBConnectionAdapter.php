<?php

namespace Custom\Connections;

class DBConnectionAdapter
{
    /**
     * @var null|mixed
     */
    private static $instance = null;

    /**
     * @var \PDO
     */
    private $conn;

    private function __construct($dbConfig)
    {
        try {
            $this->conn = new \PDO(
                $dbConfig['dsn'],
                $dbConfig['username'],
                $dbConfig['password']
            );
            //to show the exceptions in query
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public static function getInstance($dbConfig = null)
    {
        if (!self::$instance) {
            self::$instance = new DBConnectionAdapter($dbConfig);
        }
        return self::$instance;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->conn;
    }
}
