<?php

namespace App;

use Exception;
use mysqli;

/**
 * Basic application class
 */
class App
{
    /**
     * Run application
     * @return void
     * @throws Exception
     */
    public static function run() {
        $connection = new mysqli('localhost', 'bjtest', 'bjtest', 'bjtest');
        session_start();
        $response = static::callController($connection);
        echo($response);
        $connection->close();
    }

    /**
     * It makes a controller and calls the required method
     * @return mixed
     * @throws Exception
     */
    private static function callController(mysqli $connection)
    {
        [$controllerName, $methodName] = Route::resolve();
        $pageQuery = new PageQuery($_GET);
        $controller = new $controllerName($_SERVER['REQUEST_METHOD'], $pageQuery, $connection);

        if (!method_exists($controller, $methodName)) {
            throw new Exception("$methodName does not found in $controllerName");
        }

        if (!empty($_POST)) {
            $controller->setBody(self::escapeAssoc($_POST));
        }

        return $controller->$methodName();
    }

    private static function escapeAssoc(array $assoc)
    {
        $result = [];

        foreach ($assoc as $key => $value) {
            $result[$key] = htmlspecialchars(trim($value));
        }

        return $result;
    }
}
