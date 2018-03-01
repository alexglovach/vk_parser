<?php

namespace App;


class Database
{
    private $connection;

    public function __construct(array $config)
    {
        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->setFetchMode(\PDO::FETCH_ASSOC);
        $capsule->addConnection($config);
        $this->connection = $capsule->getConnection();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function table($table)
    {
        return $this->connection->table($table);
    }
}