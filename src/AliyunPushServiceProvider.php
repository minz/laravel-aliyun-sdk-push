<?php

namespace Minz\Laravel\Aliyun\Push;

use Illuminate\Support\ServiceProvider;
use function foo\func;

class AliyunPushServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton("aliyunPush", function () {
            return new AliyunPush();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . "/config/aliyun.php" => config_path('aliyun.php'),
        ]);
    }
}