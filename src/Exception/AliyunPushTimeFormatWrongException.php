<?php

namespace Minz\Laravel\Aliyun\Push\Exception;

use Exception;
use Throwable;

class AliyunPushTimeFormatWrongException extends Exception
{
    public function __construct($message = "PushTime数据必须为Y-m-d H:i:s，且必须大于当前时间", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}