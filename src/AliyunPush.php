<?php

namespace Minz\Laravel\Aliyun\Push;

use AlibabaCloud\Push\Push;
use AlibabaCloud\Client\AlibabaCloud;
use Minz\Laravel\Aliyun\Push\Exception\AliyunConfigErrorException;
use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTimeFormatWrongException;
use Minz\Laravel\Aliyun\Push\Exception\AliyunPushTypeWrongException;
use Minz\Laravel\Aliyun\Push\Target\Target;

class AliyunPush
{
    protected $accessKeyId;
    protected $accessKeySecret;

    protected $androidAppKey;
    protected $androidActivity;
    protected $androidChannel;
    protected $androidOpenType;
    protected $androidNotifyType;

    protected $iOSAppKey;
    protected $iOSApnsEnv;
    protected $pushTypeArray;

    public function __construct()
    {
        $this->pushTypeArray = ["MESSAGE", "NOTICE"];

        $this->accessKeyId = config('aliyun.accessKeyId');
        $this->accessKeySecret = config('aliyun.accessKeySecret');

        $this->androidAppKey = config('aliyun.push.android.appKey');
        $this->androidActivity = config('aliyun.push.android.activity');
        $this->androidChannel = config('aliyun.push.android.channel');
        $this->androidOpenType = config('aliyun.push.android.openType');
        $this->androidNotifyType = config('aliyun.push.android.notifyType');

        $this->iOSAppKey = config('aliyun.push.iOS.appKey');
        $this->iOSApnsEnv = config('aliyun.push.iOS.apnsEnv');

        $iOSApnsEnvArray = ["DEV", "PRODUCT"];
        if (!in_array($this->iOSApnsEnv, $iOSApnsEnvArray)) {
            throw new AliyunConfigErrorException("iOSApnsEnv参数不合法");
        }
        if (!$this->accessKeyId || !$this->accessKeySecret || !$this->androidAppKey || !$this->androidActivity || !$this->androidChannel || !$this->androidOpenType || !$this->androidNotifyType || !$this->iOSAppKey) {
            throw new AliyunConfigErrorException();
        }

        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 发送推送给iOS和Android
     *
     * @param string $title
     * @param string $body
     * @param Target $target
     * @param array|null $paramArray
     * @param string|null $pushTime
     * @param string $pushType
     * @param string|null $iOSSubTitle
     * @return bool
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function push(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, $pushType = "NOTICE", string $iOSSubTitle = null)
    {
        $this->pushNoticeToAndroid($title, $body, $target, $paramArray, $pushTime, $pushType);
        $this->pushNoticeToIOS($title, $body, $target, $paramArray, $pushTime, $pushType, $iOSSubTitle);
        return true;
    }

    /**
     * 发推送给安卓用户
     *
     * @param string $title
     * @param string $body
     * @param Target $target
     * @param array|null $paramArray
     * @param string|null $pushTime
     * @param string $pushType
     * @return \AlibabaCloud\Client\Result\Result
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function pushNoticeToAndroid(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, string $pushType = "NOTICE")
    {
        if (!in_array($pushType, $this->pushTypeArray)) {
            throw new AliyunPushTypeWrongException();
        }
        $request = Push::v20160801()
            ->push()
            ->withAppKey($this->androidAppKey)
            ->withTitle($title)
            ->withBody($body)
            ->withDeviceType("ANDROID")
            ->withAndroidActivity($this->androidActivity)
            ->withPushType($pushType)
            ->withAndroidNotifyType($this->androidNotifyType)
            ->withTarget($target->getTarget())
            ->withTargetValue($target->getTargetValue())
            ->withAndroidPopupTitle($title)
            ->withAndroidPopupBody($body)
            ->withAndroidOpenType($this->androidOpenType)
            ->withAndroidPopupActivity($this->androidActivity)
            ->withAndroidNotificationBarPriority(2)
            ->withAndroidNotificationChannel($this->androidChannel)
            ->withStoreOffline(true)
            ->withExpireTime(gmdate('Y-m-d\TH:i:s\Z', strtotime("+2 day")))
            ->withAndroidRemind(true);
        if ($paramArray) {
            $paramStr = json_encode($paramArray);
            $request->withAndroidExtParameters($paramStr);
        }
        if ($pushTime) {
            $pushTime = $this->getPushTime($pushTime);
            $request->withPushTime($pushTime);
        }
        return $request->request();
    }

    /**
     * 发推送给iOS用户
     *
     * @param string $title
     * @param string $body
     * @param Target $target
     * @param array|null $paramArray
     * @param string|null $pushTime
     * @param string $pushType
     * @param string|null $iOSSubTitle
     * @return \AlibabaCloud\Client\Result\Result
     * @throws AliyunPushTimeFormatWrongException
     * @throws AliyunPushTypeWrongException
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function pushNoticeToIOS(string $title, string $body, Target $target, array $paramArray = null, string $pushTime = null, string $pushType = "NOTICE", string $iOSSubTitle = null)
    {
        if (!in_array($pushType, $this->pushTypeArray)) {
            throw new AliyunPushTypeWrongException();
        }
        $request = Push::v20160801()
            ->push()
            ->withAppKey($this->iOSAppKey)
            ->withBody($body)
            ->withDeviceType("iOS")
            ->withPushType($pushType)
            ->withTarget($target->getTarget())
            ->withTargetValue($target->getTargetValue())
            ->withTitle($title)
            ->withIOSApnsEnv($this->iOSApnsEnv)
            ->withIOSRemind($this->getIOSRemind())
            ->withIOSBadgeAutoIncrement(true)
            ->withIOSSubtitle($iOSSubTitle);
        if ($pushTime) {
            $pushTime = $this->getPushTime($pushTime);
            $request->withPushTime($pushTime);
        }
        if ($paramArray) {
            $request->withIOSExtParameters(json_encode($paramArray));
        }
        return $request->request();
    }

    /**
     * 获取iosRemind
     *
     * @return bool
     */
    private function getIOSRemind()
    {
        if ($this->iOSApnsEnv == 'DEV') {
            return false;
        }
        return true;
    }

    /**
     * 获取pushTime
     *
     * @param string $pushTime
     * @return false|string
     * @throws AliyunPushTimeFormatWrongException
     */
    private function getPushTime(string $pushTime)
    {
        $timestamp = strtotime($pushTime);
        if ($timestamp <= time()) {
            throw new AliyunPushTimeFormatWrongException();
        }
        $transPushTime = date('Y-m-d H:i:s', $timestamp);
        if ($transPushTime != $pushTime) {
            throw new AliyunPushTimeFormatWrongException();
        }
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', $timestamp);
        return $pushTime;
    }
}