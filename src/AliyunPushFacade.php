<?php

namespace Minz\Laravel\Aliyun\Push;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class AliyunPushFacade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'aliyunPush';
    }
}