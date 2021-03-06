<?php

return [
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'delete'],
    '~^articles/(\d+)/comments$~' => [\MyProject\Controllers\CommentsController::class, 'add'],
    '~^comments/(\d+)/edit$~' => [\MyProject\Controllers\CommentsController::class, 'edit'],
    '~^comments/(\d+)/delete$~' => [\MyProject\Controllers\CommentsController::class, 'delete'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^users/register$~' => [\MyProject\Controllers\UsersController::class, 'signUp'],
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],
    '~^user/logout$~' => [\MyProject\Controllers\UsersController::class, 'logout'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^user/avatar$~' => [\MyProject\Controllers\UsersController::class, 'avatar'],
    '~^user/defaultAvatar$~' => [\MyProject\Controllers\UsersController::class, 'defaultAvatar'],
    '~^admin$~' => [\MyProject\Controllers\AdminController::class, 'view'],
    '~^admin/articles$~' => [\MyProject\Controllers\AdminController::class, 'articlesView'],
    '~^admin/comments$~' => [\MyProject\Controllers\AdminController::class, 'commentsView'],
    '~^(\d+)$~' => [\MyProject\Controllers\MainController::class, 'page'],
    '~^before/(\d+)$~' => [\MyProject\Controllers\MainController::class, 'before'],
    '~^after/(\d+)$~' => [\MyProject\Controllers\MainController::class, 'after'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
];
//То есть это просто массив, у которого ключи – это регулярка для адреса, а значение – это массив с двумя значениями –
// именем контроллера и названием метода.