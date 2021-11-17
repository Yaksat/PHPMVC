<?php

namespace MyProject\Cli;

class TestCron extends AbstractCommand
{
    protected function checkParams()
    {
        $this->ensureParamExists('x');
        $this->ensureParamExists('y');
        $this->ensureParamExists('sleep');
    }

    public function execute()
    {
        //чтобы проверить работу скрипта, будем записывать в файлик 1.log текущую дату и время
        sleep($this->getParam('sleep'));
        file_put_contents('C:\\1.log', date(DATE_ISO8601) . PHP_EOL, FILE_APPEND);
    }
}