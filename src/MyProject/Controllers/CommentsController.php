<?php
namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;

class CommentsController extends AbstractController
{
    public function add (int $articleId): void
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

                $comments = Comment::findAll();

                $isEditable = false;


                if ($article === null) {
                    throw new NotFoundException();
                }

                if ($this->user->isAdmin()) {
                    $isEditable = true;
                }

                $this->view->renderHtml('articles/view.php', ['article' => $article, 'isEditable' => $isEditable, 'comments' => $comments, 'error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        header('Location: /articles/' . $articleId);
    }

    public function edit(int $commentId): void
    {
        $comment = Comment::getById($commentId);

        if ($comment === null){
            throw new NotFoundException();
        }

        if ($this->user === null){
            throw new UnauthorizedException();
        }

        if (!($this->user->isAdmin() || $this->user->getId() === $comment->getUserId())){
            throw new ForbiddenException('Редактировать данный комментарий может только админ или автор комментария');
        }

        if (!empty($_POST)){
            try {
                $comment->updateFromArray($_POST);
            } catch (InvalidArgumentException $e){
                $this->view->renderHtml('comments/edit.php', ['comment' => $comment, 'error' => $e->getMessage()]);
                return;
            }

            header('Location: /articles/' . $comment->getArticleId() . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('comments/edit.php', ['comment' => $comment]);
    }

    public function delete(int $commentId): void
    {
        $comment = Comment::getById($commentId);

        if ($comment === null){
            throw new NotFoundException();
        }

        if ($this->user === null){
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()){
            throw new ForbiddenException('Удалить данный комментарий может только админ');
        }

        $comment->delete();

        $this->view->renderHtml('comments/delete.php');

    }
}