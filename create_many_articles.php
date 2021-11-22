<?php

spl_autoload_register(function (string $className) {
    require_once __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';
});

$db = \MyProject\Services\Db::getInstance();

$query = 'INSERT INTO articles (`author_id`, `name`, `text`) VALUES (1, \'Статья #1\', \'Текст статьи 1\')';
for ($i = 2; $i <= 100; $i++) {
    $query .= sprintf(
        ',(1, \'%s\', \'%s\')',
        'Статья #' . $i,
        'Текст статьи ' . $i
    );

    if ($i % 10 === 0) {
        $db->query($query);
        echo $i . PHP_EOL;
        $i++;
        $query = 'INSERT INTO articles (`author_id`, `name`, `text`) VALUES (1, \'Статья #' . $i . '\', \'Текст статьи ' . $i . '\')';
    }
}