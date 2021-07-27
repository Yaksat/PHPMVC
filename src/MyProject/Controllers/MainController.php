<?php

namespace MyProject\Controllers;

class MainController
{
    public function main(): void
    {
        echo 'Главная страница';
    }

    public function sayHello(string $name): void
    {
        echo 'Привет, ' . $name;
    }

    public function sayBye(string $name): void
    {
        echo 'Пока, ' . $name;
    }
}