<?php


namespace Minz\Laravel\Aliyun\Push\Target;


use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTargetValueWrongException;

class AllTarget implements Target
{
    public function __construct(array $targetValue = null)
    {

    }

    public function getTarget(): string
    {
        return "ALL";
    }

    public function getTargetValue(): string
    {
        return "ALL";
    }
}