<?php

return [
    '~^hello/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
];
//То есть это просто массив, у которого ключи – это регулярка для адреса, а значение – это массив с двумя значениями –
// именем контроллера и названием метода.