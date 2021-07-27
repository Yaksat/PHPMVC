<?php

spl_autoload_register(function (string $className){
    require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

//РОУТИНГ
//с помощью регулярки научимся понимать, что текущий адрес:
// http://phpmvc/hello/* , где * - вообще любая строка.
/*
$route = $_GET['route'] ?? '';

$pattern = '~^hello/(.*)$~';
preg_match($pattern, $route, $matches);

if (!empty($matches)) {
    $controller = new MyProject\Controllers\MainController();
    $controller->sayHello($matches[1]);
    return;
}

//добавим обработку случая, когда мы просто зашли на http://phpmvc/. В таком случае переменная route будет
// пустой строкой. Регулярка для такого случая - ^$. Да, просто начало строки и конец строки.
$pattern = '~^$~';
preg_match($pattern, $route, $matches);

if (!empty($matches)) {
    $controller = new MyProject\Controllers\MainController();
    $controller->main();
    return;
}

//Остаётся только добавить обработку случая, когда ни одна из этих регулярок не подошла и просто вывести сообщение о том
// что страница не найдена.
echo 'Страница не найдена';
*/


//Универсальная система роутинга. Создали файл routes.php.
$route = $_GET['route'] ?? '';
$routes = require __DIR__ . '/../src/routes.php';

//пробежаться по нему foreach-ом и найти соответствие по регулярке для текущего адреса. Как только совпадение найдено,
// нужно остановить перебор.
$isRouteFound = false;
foreach ($routes as $pattern => $controllerAndAction) {
    preg_match($pattern, $route, $matches);
    if (!empty($matches)) {
        $isRouteFound = true;
        break;
    }
}

if (!$isRouteFound) {
    echo 'Страница не найдена!';
    return;
}

unset($matches[0]); //В нулевом элементе лежит полное совпадение по паттерну, которое нам не нужно. Удаляем его.

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

$controller = new $controllerName();
$controller->$actionName(...$matches);

/*
$controller = new \MyProject\Controllers\MainController();

if (!empty($_GET['name'])) {
    $controller->sayHello($_GET['name']);
} else {
    $controller->main();
}
*/
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

/*
$pattern = '/\/(?P<controller>.+)\/(?P<id>[0-9]+)/';
$url = '/post/892';
preg_match($pattern,$url,$matches);
$controller = $matches['controller'];
$id = $matches['id'];
echo $controller . $id;
*/