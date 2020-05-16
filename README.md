<h1 align="center">laravel aliyun push package</h1>

<p align="center">
Laravel based on <a href="https://github.com/aliyun/alibabacloud-sdk">alibabacloud-sdk</a><br>
阿里云移动推送open-api文档<a href="https://help.aliyun.com/document_detail/48038.html?spm=a2c4g.11174283.6.560.12ba6d16w7Wltm">移动推送文档</a>
</p>



## Requirement

-   PHP >= 7.0

## Installing

```shell
$ composer require "minz/laravel-aliyun-push" -vvv
```

## Configuration

1. After installing the library, register the `Minz\Laravel\Aliyun\Push\AliyunPushServiceProvider::class` in your `config/app.php` file:

```php
'providers' => [
    ......
    Minz\Laravel\Aliyun\Push\AliyunPushServiceProvider::class,
],
```

> Laravel 5.5+ skip
2. After installing the library, register the `'AliyunPush' => Minz\Laravel\Aliyun\Push\AliyunPushFacade::class,` in your `config/app.php` file:

```php
'providers' => [
    ......
    'AliyunPush' => Minz\Laravel\Aliyun\Push\AliyunPushFacade::class,
],
```
3. publish config file:

```php
php artisan vendor:publish --provider="Minz\Laravel\Aliyun\Push\AliyunPushServiceProvider"
```

## api ducument
```php
    /**
     * 发送推送给iOS和Android
     *
     * @param string $title 标题
     * @param string $body 内容
     * @param Minz\Laravel\Aliyun\Push\Target\Target $target 
     * @param array|null $paramArray 额外参数
     * @param string|null $pushTime 定时发送 Y-m-d H:i:s
     * @param string $pushType 推送类型 NOTICE:推送（默认） MESSAGE:消息
     * @param string|null $iOSSubTitle iOS推送副标题
     * @return bool 
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function push(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, $pushType = "NOTICE", string $iOSSubTitle = null)


    /**
     * 发推送给安卓用户
     *
     * @param string $title 标题
     * @param string $body 内容
     * @param Minz\Laravel\Aliyun\Push\Target\Target $target 
     * @param array|null $paramArray 额外参数
     * @param string|null $pushTime 定时发送 Y-m-d H:i:s
     * @param string $pushType 推送类型 NOTICE:推送（默认） MESSAGE:消息
     * @return \AlibabaCloud\Client\Result\Result
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function pushNoticeToAndroid(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, string $pushType = "NOTICE")
    

    /**
     * 发推送给iOS用户
     *
     * @param string $title 标题
     * @param string $body 内容
     * @param Minz\Laravel\Aliyun\Push\Target\Target $target 
     * @param array|null $paramArray 额外参数
     * @param string|null $pushTime 定时发送 Y-m-d H:i:s
     * @param string $pushType 推送类型 NOTICE:推送（默认） MESSAGE:消息
     * @param string|null $iOSSubTitle iOS推送副标题
     * @return \AlibabaCloud\Client\Result\Result
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
     public function pushNoticeToIOS(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, string $pushType = "NOTICE", string $iOSSubTitle = null)     
```

## usage
### facade 门面模式
```php
use Minz\Laravel\Aliyun\Push\Target\DeviceTarget; //可以选择多个Target object

$target = new DeviceTarget(["xx", "xx", "xx"]);
$pushTime = gmdate('Y-m-d H:i:s', strtotime("+1 minute"));
$result = AliyunPush::push("标题", "body", $target, ['k' => 'v'], $pushTime, "NOTICE", "subTitle");
```

### 普通模式
```php
use Minz\Laravel\Aliyun\Push\Target\TagTarget;
use Minz\Laravel\Aliyun\Push\AliyunPush;
//单个tag
$target = new TagTarget(["男性"]);
//带有逻辑关系多个tag
$target = new \Minz\Laravel\Aliyun\Push\Target\TagTarget([
        "and" => [
            [
                "tag" => "男"
            ],
            [
                "or" => [
                    "tag" => '活跃'
                ]
            ]
        ],
    ]);
$pushTime = gmdate('Y-m-d H:i:s', strtotime("+1 minute"));
$result = (new AliyunPush())->push("标题", "body", $target, ['k' => 'v'], $pushTime, "NOTICE", "subTitle");
```

## depend
-   [alibabacloud-sdk](https://github.com/aliyun/alibabacloud-sdk)

## License
MIT

