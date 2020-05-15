<?php

namespace Minz\Laravel\Aliyun\Push\Target;

use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTargetValueWrongException;

class AccountTarget implements Target
{
    protected $targetValue;
    public function __construct(array $targetValue = null)
    {
        if (!$targetValue || count($targetValue) > 100) {
            throw new AliyunPushTargetValueWrongException();
        }
        $this->targetValue = $targetValue;
    }

    public function getTarget(): string
    {
        return "ACCOUNT";
    }

    public function getTargetValue(): string
    {
        return implode(",", $this->targetValue);
    }
}