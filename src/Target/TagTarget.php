<?php

namespace Minz\Laravel\Aliyun\Push\Target;

use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTargetValueWrongException;

class TagTarget implements Target
{
    protected $targetValue;
    public function __construct(array $targetValue = null)
    {
        if (!$targetValue) {
            throw new AliyunPushTargetValueWrongException();
        }
        $this->targetValue = $targetValue;
    }

    public function getTarget(): string
    {
        return "TAG";
    }

    public function getTargetValue(): string
    {
        $depth = $this->getTargetDepth($this->targetValue);
        if ($depth == 1 && count($this->targetValue) == 1) {
            return $this->targetValue[0];
        }
        return json_encode($this->targetValue);
    }

    private function getTargetDepth(array $targetValue)
    {
        $maxDepth = 1;
        foreach ($targetValue as $value) {
            if (is_array($value)) {
                $depth = $this->getTargetDepth($value) + 1;
                $maxDepth = max($depth, $maxDepth);
            }
        }
        return $maxDepth;
    }
}