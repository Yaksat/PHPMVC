<?php

namespace MyProject\Services;

use MyProject\Models\Articles\Article;

class CacheArticleID {
    public static function getLastArticleID(): int
    {
        $cacheFile = __DIR__ . '/../../../cache/last_article_id';
        $value_from_cache = file_get_contents($cacheFile);
        if (!empty($value_from_cache)) {
            return (int)$value_from_cache;
        }

        $lastID = Article::getLastID();
        file_put_contents($cacheFile, $lastID);
        return $lastID;
    }

    public static function putLastArticleID(): int
    {
        $cacheFile = __DIR__ . '/../../../cache/last_article_id';
        $lastID = Article::getLastID();
        file_put_contents($cacheFile, $lastID);
        return $lastID;
    }
}

