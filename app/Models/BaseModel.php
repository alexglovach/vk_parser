<?php


namespace App\Models;

use App\Database;

/**
 * @property \App\Database connection
 */
class BaseModel
{
    private $connection;

    const TABLE = null;

    public function __construct(Database $db)
    {
        $this->connection = $db;
    }

    protected function insert($data)
    {
        return $this->connection->table(static::TABLE)->insert($data);
    }

    protected function selectFirst($criteria)
    {
        $q = $this->connection->table(static::TABLE);
        foreach ($criteria as $item) {
            $q->where($item[0], $item[1], $item[2]);
        }
        return (array)$q->first();
    }
}