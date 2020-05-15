<?php

namespace Minz\Laravel\Aliyun\Push\Target;

use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTargetValueWrongException;

class AliasTarget
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
        return "ALIAS";
    }

    public function getTargetValue(): string
    {
        return implode(",", $this->targetValue);
    }
}