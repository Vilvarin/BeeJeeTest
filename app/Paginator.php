<?php

namespace App;

use Exception;
use mysqli;

/**
 * Class for working with pagination
 */
class Paginator
{
    const LIMIT = 3;

    public int $prevPage;
    public int $currPage;
    public int $nextPage;
    public int $offset;
    public int $lastPage;

    protected PageQuery $_query;
    protected mysqli $_connection;

    public function __construct(PageQuery $query, mysqli $connection)
    {
        $this->_query = $query;
        $this->_connection = $connection;

        $queryPage = $this->_query->getKey('page');
        $this->currPage = empty($queryPage) ? 1 : intval($queryPage);
        $this->offset = ($this->currPage - 1) * self::LIMIT;

        $rowCount = $this->queryRowCount();
        $this->lastPage = intdiv($rowCount, self::LIMIT) + min($rowCount % self::LIMIT, 1);

        $this->prevPage = max($this->currPage - 1, 1);
        $this->nextPage = min($this->currPage + 1, $this->lastPage);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function queryRowCount()
    {
        $result = $this->_connection->query('SELECT count(*) row_count from todo_items');

        if (!$result) {
            throw new Exception($this->_connection->error);
        }

        return $result->fetch_row()[0];
    }

    public function hasPrevPage(): bool
    {
        return $this->currPage > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->currPage < $this->lastPage;
    }
}
