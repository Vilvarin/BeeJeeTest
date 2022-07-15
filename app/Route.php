<?php

namespace App;

use Exception;

/** Static class for registering and resolving routes */
class Route
{
    const BASE_URL = '';

    /** We should not instantiate this class */
    private function __construct() {}

    /**
     * @var string[][]
     */
    private static array $routeTable = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Write route in table
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     * @throws Exception
     */
    public static function register(string $method, string $uri, string $action)
    {
        if (in_array($method, ['GET', 'POST'])) {
            static::$routeTable[$method][self::BASE_URL . $uri] = $action;
        } else {
            throw new Exception('Invalid route method');
        }
    }

    /**
     * Register route with GET method
     * @param string $uri
     * @param string $action
     * @return void
     * @throws Exception
     */
    public static function get(string $uri, string $action)
    {
        static::register('GET', $uri, $action);
    }

    /**
     * Register route with POST method
     * @param string $uri
     * @param string $action
     * @return void
     * @throws Exception
     */
    public static function post(string $uri, string $action)
    {
        static::register('POST', $uri, $action);
    }

    /**
     * Find controller method in route table
     * @throws Exception
     */
    public static function resolve(): array
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('?', $path)[0];
        $action = explode('::', static::$routeTable[$method][$path]);

        if (empty($action)) {
            throw new Exception('Could not find route in table');
        }

        if (count($action) !== 2) {
            throw new Exception('Invalid controller action format');
        }

        return $action;
    }
}
