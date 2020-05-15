<?php

namespace Minz\Laravel\Aliyun\Push\Exception;

use Exception;
use Throwable;

class AliyunPushTargetValueWrongException extends Exception
{
    public function __construct($message = "Target初始化传入数据错误", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}