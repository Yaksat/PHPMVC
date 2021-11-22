<?php
$startTime = microtime(true);
require __DIR__ . '/../vendor/autoload.php';

try {
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
} catch (\MyProject\Exceptions\UnauthorizedException $e) {
    $view = new MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyProject\Exceptions\ForbiddenException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage(), 'user' => \MyProject\Models\Users\UsersAuthService::getUserByToken()]);
    //передаем user, чтобы отображать залогиненного пользователя на странице с ошибкой
}
$endTime = microtime(true);
printf('<div style="text-align: center; padding: 5px"> Время генерации страницы: %f</div>', $endTime - $startTime);