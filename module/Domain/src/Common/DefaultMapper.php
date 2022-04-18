<?php

namespace Domain\Common;

use Custom\Connections\DBConnectionAdapter;

class DefaultMapper
{
    /**
     * @var \PDO
     */
    protected $conn;

    protected $fetch_assoc = \PDO::FETCH_ASSOC;

    public function __construct()
    {
        $this->conn = DBConnectionAdapter::getInstance()->getConnection();
    }
}
