<?php

namespace MyProject\Controllers;

use MyProject\Services\Db;
use MyProject\View\View;

class ArticlesController
{
    /** @var View */
    private $view;

    /** @var Db */
    private $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function view(int $articleId)
    {
        $nickName = 'Автор не найден';

        $result = $this->db->query(
            'SELECT * FROM `articles` WHERE id = :id;',
            [':id' => $articleId]
        );

        if ($result === []) {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $resultUser = $this->db->query(
            'SELECT `nickname` FROM `users` WHERE id = :id;',
            [':id' => $result[0]['author_id']]
        );

        if ($resultUser !== []) {
            $nickName = $resultUser[0]['nickname'];
        }
        
        $this->view->renderHtml('articles/view.php', ['article' => $result[0], 'nickName' => $nickName]);
    }
}
