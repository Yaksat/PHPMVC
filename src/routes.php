<?php

return [
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
];
//То есть это просто массив, у которого ключи – это регулярка для адреса, а значение – это массив с двумя значениями –
// именем контроллера и названием метода.