<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;

class AdminController extends AbstractController
{
    public function view()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут попасть на эту страницу');
        }

        $this->view->renderHtml('admin/adminMain.php');
    }

    public function articlesView ()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут попасть на эту страницу');
        }

        $articles = Article::findAllShort(); //вместо этого сделать вывод статей не полностью
        $this->view->renderHtml('admin/articles.php', ['articles' => $articles]);
    }

    public function commentsView ()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут попасть на эту страницу');
        }

        $comments = Comment::findAll();
        $this->view->renderHtml('admin/comments.php', ['comments' => $comments]);
    }
}