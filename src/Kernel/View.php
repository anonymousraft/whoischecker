<?php

declare(strict_types=1);

namespace App\Kernel;

class View
{
    public static function render(string $template, array $data = []): void
    {
        $data['base'] = defined('BASE_PATH') ? BASE_PATH : '/';
        extract($data);
        $templatePath = dirname(__DIR__, 2) . "/templates/{$template}.php";

        if (!file_exists($templatePath)) {
            throw new \Exception("Template not found: {$template}");
        }

        require_once $templatePath;
    }
}
