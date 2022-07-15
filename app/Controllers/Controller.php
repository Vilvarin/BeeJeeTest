<?php

namespace App\Controllers;

use App\PageQuery;
use Exception;
use mysqli;
use App\View;

/**
 * Basic controller
 */
abstract class Controller
{
    protected mysqli $connection;
    protected PageQuery $query;
    protected $body = null;
    protected string $method;

    public function __construct(string $method, PageQuery $query, mysqli $connection)
    {
        $this->method = $method;
        $this->query = $query;
        $this->connection = $connection;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Render view by name
     * @param string $templateName
     * @param array $params
     * @return void
     * @throws Exception
     */
    protected function render(string $templateName, array $params = []): string
    {
        $baseView = new View('template');
        $contentView = new View($templateName);

        return $baseView->render([
            'content' => $contentView,
            'params' => $params,
        ]);
    }

    protected function redirect(string $url)
    {
        ob_start();
        header('Location: ' . $url);
        ob_end_flush();
    }

    protected function sendClientError($message)
    {
        http_response_code(400);

        return $message;
    }
}
