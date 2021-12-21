<?php

namespace DoubleC\LaravelShopify\Http\Exceptions;

use Exception;
use Throwable;

class VerifyRequestException extends Exception
{
    private string $errorParam;
    private array $params;

    /**
     * @return string
     */
    public function getErrorParam(): string
    {
        return $this->errorParam;
    }

    /**
     * @param string $errorParam
     */
    public function setErrorParam(string $errorParam): void
    {
        $this->errorParam = $errorParam;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

}
