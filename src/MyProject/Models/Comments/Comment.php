<?php

namespace MyProject\Models\Comments;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;

class Comment extends ActiveRecordEntity
{
    /** @var int */
    protected $userId;

    /** @var int */
    protected $articleId;

    /** @var string */
    protected $text;

    /** @var string */
    protected $createdAt;

    protected static function getTableName(): string
    {
        return 'comments';
    }

    public function getUser(): User
    {
        return User::getById($this->userId);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public static function createFromArray(string $commentText, int $userId, int $articleId): Comment
    {
        if (empty($commentText)){
            throw new InvalidArgumentException('Введите текст комментария');
        }

        $comment = new Comment();

        $comment->text = $commentText;
        $comment->userId = $userId;
        $comment->articleId = $articleId;

        $comment->save();

        return $comment;
    }

}
