<?php

require __DIR__ . '/../../vendor/autoload.php';

$entity = [
    'kek' => 'cheburek',
    'lol'=> [
        'foo' => 'bar'
    ]
];

header('Content-type: application/json; charset=utf-8');
echo json_encode($entity);