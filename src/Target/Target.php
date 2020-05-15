<?php

namespace Minz\Laravel\Aliyun\Push\Target;

interface Target
{
    public function __construct(array $targetValue = null);
    public function getTarget() :string;
    public function getTargetValue() :string;
}