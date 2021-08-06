<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Services\Db;
use MyProject\View\View;

class MainController
{
    /** @var View  */
    private $view;

    /** @var Db  */
    private $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function main(): void
    {
        $articles = $this->db->query('SELECT * FROM `articles`;', [], Article::class);
        var_dump($articles);
        return;
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }

    public function sayHello(string $name): void
    {
        $this->view->renderHtml('main/hello.php', ['name' => $name, 'title' => 'Страница приветствия']);
    }

    public function sayBye(string $name): void
    {
        $this->view->renderHtml('main/sayBye.php', ['name' => $name, 'title' => 'Страница выхода']);
    }
}