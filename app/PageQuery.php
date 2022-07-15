<?php

namespace App;

/**
 * Object for storing and receiving GET query values.
 * Escape values when instantiated.
 */
class PageQuery
{
    protected array $_query = [];

    /**
     * @param array<string, string> $queryValues
     */
    public function __construct(array $queryValues)
    {
        foreach ($queryValues as $key => $value) {
            if (!empty($value)) {
                $this->_query[$key] = htmlspecialchars(trim(strval($value)));
            }
        }
    }

    /**
     * Get query value by key
     * @param string $name
     * @return mixed
     */
    public function getKey(string $name): string
    {
        return $this->_query[$name] ?? '';
    }

    /**
     * This checks if a key is found
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return isset($this->_query[$key]);
    }

    /**
     * This checks if a key is equal to a passed value
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function keyEquals(string $key, string $value): bool
    {
        return $this->_query[$key] === $value;
    }

    /**
     * Get prepared query string for insert to url
     * @return string
     */
    public function getQueryString(): string
    {
        return '?' . http_build_query($this->_query);
    }

    /**
     * Merge existed with new values and return new PageQuery object
     * @param array $newValues
     * @return $this
     */
    public function getModifiedCopy(array $newValues): self
    {
        foreach ($newValues as $value)
        $modifiedValues = array_merge($this->_query, $newValues);

        return new PageQuery($modifiedValues);
    }

    /**
     * Will return a query for sorting
     * @param string $by
     * @return string
     */
    public function getSortQueryString(string $by)
    {
        $sortDir = $this->getSortDir($by);

        return $this->getModifiedCopy([
            'sortby' => $by,
            'sort' => $sortDir
        ])->getQueryString();
    }

    /**
     * Will return a sort direction
     * @return string
     */
    public function getSortDir(string $by): string
    {
        if ($this->hasKey('sortby') && $this->keyEquals('sortby', $by)) {
            return $this->hasKey('sort')
                ? $this->keyEquals('sort', 'asc') ? 'desc' : 'asc'
                : 'desc';
        } else {
            return 'asc';
        }
    }

    /**
     * Will return a query string without sorting fields
     * @return string
     */
    public function getResetSortQueryString(): string
    {
        return $this->getModifiedCopy([
            'sortby' => null,
            'sort' => null
        ])->getQueryString();
    }

    /**
     * Will return query string to the next page navigation
     * @param Paginator $paginator
     * @return string
     */
    public function getNextPageQueryString(Paginator $paginator): string
    {
        $page = $paginator->currPage + 1;

        return $this->getModifiedCopy([
            'page' => $page
        ])->getQueryString();
    }

    /**
     * Will return query string to the previous page navigation
     * @param Paginator $paginator
     * @return string
     */
    public function getPrevPageQueryString(Paginator $paginator): string
    {
        $page = $paginator->currPage - 1;

        return $this->getModifiedCopy([
            'page' => $page
        ])->getQueryString();
    }
}
