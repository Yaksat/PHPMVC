<?php
try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
    });

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
        throw new \MyProject\Exceptions\NotFoundException();
    }

    unset($matches[0]); //В нулевом элементе лежит полное совпадение по паттерну, которое нам не нужно. Удаляем его.

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (\MyProject\Exceptions\DbException $e) {
    $view = new MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\MyProject\Exceptions\NotFoundException $e) {
    $view = new MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
}