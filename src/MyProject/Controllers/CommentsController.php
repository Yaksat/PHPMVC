<?php
namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;

class CommentsController extends AbstractController
{
    public function add (int $articleId)
    {
        //проверка на авторизованность пользователя
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)){
            try {
                $comment = Comment::createFromArray($_POST['comment'], $this->user->getId(), $articleId);
            } catch (InvalidArgumentException $e) {
                $article = Article::getById($articleId);

                $isEditable = false;

                if ($article === null) {
                    throw new NotFoundException();
                }

                if ($this->user !== null && $this->user->isAdmin()) {
                    $isEditable = true;
                }

                $this->view->renderHtml('articles/view.php', ['article' => $article, 'isEditable' => $isEditable, 'error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        header('Location: /articles/' . $articleId);
    }
}