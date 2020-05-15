<?php

namespace Minz\Laravel\Aliyun\Push\Exception;

use Exception;

class AliyunConfigErrorException extends Exception
{
    public function __construct($message = "缺少配置,请检查config/aliyun.php配置", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}