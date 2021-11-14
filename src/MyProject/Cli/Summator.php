<?php

namespace MyProject\Cli;

use MyProject\Exceptions\CliException;

class Summator
{
    /** @var array */
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->checkParams();
    }

    public function execute()
    {
        echo $this->getParam('a') + $this->getParam('b');
    }

    private function checkParams()
    {
        $this->ensureParamExists('a');
        $this->ensureParamExists('b');
    }

    private function getParam(string $paramName)
    {
        return $this->params[$paramName] ?? null;
    }

    private function ensureParamExists(string $paramName)
    {
        if (!isset($this->params[$paramName])) {
            throw new CliException('Param with name "' . $paramName . '" is not set!');
        }
    }
}
