<?php

namespace App\Core;

class Controller
{
    protected function view($path, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/" . $path . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View $path not found");
        }
    }

    protected function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }

    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }
}
