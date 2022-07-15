<?php

namespace App;

use Exception;

/**
 * Basic view class
 */
class View
{
    protected string $templateName;

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Render view
     * @param array $params
     * @return void
     * @throws Exception
     */
    public function render(array $params = []): string
    {
        $pathToFile = __DIR__ . '/Views/' . $this->templateName . '.php';

        if (!file_exists($pathToFile)) {
            throw new Exception('View can not be found');
        }

        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = htmlspecialchars($value);
            }
        }

        extract($params);
        ob_start();
        include($pathToFile);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
