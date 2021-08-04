<?php

namespace MyProject\View;

class View
{
    private $templatesPath;

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code); // код ответа для страницы: 200-все хорошо, 404-страница не найдена
        extract($vars);

        //в PHP есть возможность весь поток вывода положить во временный буфер вывода
        ob_start();
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer; //передаем данные в поток вывода
    }
}