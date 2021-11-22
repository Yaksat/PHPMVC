<?php

namespace MyProject\Models\Articles;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use MyProject\Services\Db;

class Article extends ActiveRecordEntity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var string */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return User::getById($this->authorId) ? : null;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }

    //создание новой статьи
    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();

        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        $article->save();

        return $article;
    }

    //редактирование статьи
    public function updateFromArray(array $fields): void
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();
    }

    public static function findAllShort(): array
    {
        $articles = Article::findAll();
        foreach ($articles as $article) {
            $article->text =  mb_strimwidth($article->text, 0, 100, "...");
        }
        return $articles;
    }

    public function getParsedText(): string
    {
        $parser = new \Parsedown();
        return $parser->text($this->getText());
    }

    /**
     * @param int $id
     * @param int $limit
     * @return Article[]
     */
    public static function getPageBefore(int $id, int $limit): array
    {
        $db = Db::getInstance();
        $sql = sprintf('SELECT * FROM (SELECT * FROM '.self::getTableName().' WHERE id > :id ORDER BY id ASC LIMIT %d) as articles ORDER BY id DESC;', $limit);
        return $db->query($sql, ['id' => $id], self::class);
    }

    /**
     * @param int $id
     * @param int $limit
     * @return Article[]
     */
    public static function getPageAfter(int $id, int $limit): array
    {
        $db = Db::getInstance();
        $sql = sprintf('SELECT * FROM '.self::getTableName().' WHERE id < :id ORDER BY id DESC LIMIT %d;', $limit);
        return $db->query($sql, ['id' => $id], self::class);
    }

    public static function hasNextPage(int $pageLastId): bool
    {
        // Проверяем есть ли следующая страница
        $db = Db::getInstance();
        $sql = 'SELECT id FROM '.self::getTableName().' WHERE id < :id LIMIT 1;';
        $result = $db->query($sql, ['id' => $pageLastId]);
        return !empty($result);
    }

    public static function hasPreviousPage(int $pageFirstId): bool
    {
        // Проверяем есть ли предыдущая страница
        $db = Db::getInstance();
        $sql = 'SELECT id FROM '.self::getTableName().' WHERE id > :id LIMIT 1;';
        $result = $db->query($sql, ['id' => $pageFirstId]);
        return !empty($result);
    }

    public static function getLastID(): ?int
    {
        $db = Db::getInstance();
        $sql = 'SELECT id FROM '.self::getTableName().' ORDER BY id DESC LIMIT 1;';
        $result = $db->query($sql);
        return !empty($result) ? $result[0]->id : null;
    }
}