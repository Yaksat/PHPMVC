<?php

spl_autoload_register(function (string $className){
    require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

$controller = new \MyProject\Controllers\MainController();

if (!empty($_GET['name'])) {
    $controller->sayHello($_GET['name']);
} else {
    $controller->main();
}

//+Информация по регулярным выражениям.
/*
$pattern = '/Меняем автора статьи (?P<articleId>[0-9]+) с "(.+)" на "(.+)"./';
$string = 'Меняем автора статьи 123 с "Иван" на "Пётр".';

preg_match($pattern, $string, $matches);
var_dump($matches);

$articleId = $matches['articleId'];
$oldAuthor = $matches[2];
$newAuthor = $matches[3];
*/

$pattern = '/\/(?P<controller>.+)\/(?P<id>[0-9]+)/';
$url = '/post/892';
preg_match($pattern,$url,$matches);
$controller = $matches['controller'];
$id = $matches['id'];
echo $controller . $id;