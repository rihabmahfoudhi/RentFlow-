<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        $layoutFile = __DIR__ . '/../views/layouts/main.php';
        $viewCandidates = [
            __DIR__ . '/../views/pages/' . $view . '.php',
            __DIR__ . '/../views/' . $view . '.php',
        ];

        $viewFile = null;

        foreach ($viewCandidates as $candidate) {
            if (is_file($candidate)) {
                $viewFile = $candidate;
                break;
            }
        }

        if ($viewFile === null) {
            throw new \RuntimeException('View not found: ' . implode(', ', $viewCandidates));
        }

        extract($data, EXTR_SKIP);
        require $layoutFile;
    }

    protected function redirect(string $route): void
    {
        header('Location: index.php?route=' . ltrim($route, '/'));
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * @return array<string, string>|null
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;

        if (is_array($flash)) {
            unset($_SESSION['flash']);
            return $flash;
        }

        return null;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderStandalone(string $viewPath, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . ltrim($viewPath, '/') . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $viewFile);
        }

        extract($data, EXTR_SKIP);
        require $viewFile;
    }
}
