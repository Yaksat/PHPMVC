<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotAdminException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;
use MyProject\Services\CacheArticleID;

class ArticlesController extends AbstractController
{
    public function view(int $articleId): void
    {
        $article = Article::getById($articleId);
        $comments = Comment::findByColumn('article_id', $articleId);

        $isEditable = false;

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user !== null && $this->user->isAdmin()) {
            $isEditable = true;
        }

        $this->view->renderHtml('articles/view.php', ['article' => $article, 'isEditable' => $isEditable, 'comments' => $comments]);
    }

    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут редактировать статьи');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(): void
    {
        //проверка на авторизованность пользователя
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Только пользователи с ролью admin могут добавлять статьи');
        }

        if (!empty($_POST)){
            try {
                //создаем статью
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }

            CacheArticleID::putLastArticleID(); // Добавляем id последней статьи в кэш.
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/add.php');
    }

    public function delete(int $articleId): void
    {
        $article = Article::getById($articleId);

        if($article === null) {
            $this->view->renderHtml('errors/notFound.php', [], 404);
            return;
        }

        // удаляем все комментарии связанные с этой статьей
        Comment::deleteCommentsFromArticle($articleId);

        $article->delete();
        CacheArticleID::putLastArticleID(); // Добавляем id последней статьи в кэш.
        $this->view->renderHtml('articles/delete.php');
    }
}
