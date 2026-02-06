<?php
class View
{
    public static function render(string $view, array $data = []): void
    {
        self::renderWithLayout($view, $data, 'layout/base');
    }

    public static function renderPublic(string $view, array $data = []): void
    {
        self::renderWithLayout($view, $data, 'layout/public');
    }

    private static function renderWithLayout(string $view, array $data, string $layout): void
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../views/' . $layout . '.php';
        require $layoutFile;
    }

    public static function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
