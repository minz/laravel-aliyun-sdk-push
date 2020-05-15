<?php

namespace Minz\Laravel\Aliyun\Push\Exception;

use Exception;
use Throwable;

class AliyunPushTypeWrongException extends Exception
{
    public function __construct($message = "PushType初始化传入数据错误,支持MESSAGE,NOTICE", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}