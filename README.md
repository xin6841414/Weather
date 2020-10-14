<h1 align="center"> weather </h1>

<p align="center"> 基于高德开放平台的php天气信息组件.</p>

[![Build Status](https://travis-ci.org/xin6841414/Weather.svg?branch=master)](https://travis-ci.org/xin6841414/Weather)

[高德开放平台](https://lbs.amap.com/dev/id/newuser)

## 安装

```shell
$ composer require xin6841414/weather -vvv
```


## 配置

- 在使用本扩展之前， 你需要去 [高德开放平台](https://lbs.amap.com/dev/id/newuser) 注册账号，然后创建应用，获取应用的API key。
## 使用

```php
use Xin6841414\Weather\Weather;

$key = 'XXXXXXXXXXXXXXXXX';

$weather = new Weather($key);

```
## 获取实时天气
```php
$response = $weather->getWeather('青岛');
```
### 示例
```php

{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "lives": [
        {
            "province": "山东",
            "city": "青岛市",
            "adcode": "370200",
            "weather": "阴",
            "temperature": "16",
            "winddirection": "北",
            "windpower": "≤3",
            "humidity": "39",
            "reporttime": "2020-10-13 08:25:20"
        }
    ]
}
```
### 获取近期天气预报
```php
$response = $weather->getWeather('青岛', 'all');

```
### 示例
```php
{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "forecasts": [
        {
            "city": "青岛市",
            "adcode": "370200",
            "province": "山东",
            "reporttime": "2020-10-13 09:25:18",
            "casts": [
                {
                    "date": "2020-10-13",
                    "week": "2",
                    "dayweather": "多云",
                    "nightweather": "小雨",
                    "daytemp": "18",
                    "nighttemp": "14",
                    "daywind": "南",
                    "nightwind": "南",
                    "daypower": "4",
                    "nightpower": "4"
                },
                {
                    "date": "2020-10-14",
                    "week": "3",
                    "dayweather": "小雨",
                    "nightweather": "阴",
                    "daytemp": "16",
                    "nighttemp": "13",
                    "daywind": "北",
                    "nightwind": "北",
                    "daypower": "4",
                    "nightpower": "4"
                },
                {
                    "date": "2020-10-15",
                    "week": "4",
                    "dayweather": "多云",
                    "nightweather": "小雨",
                    "daytemp": "17",
                    "nighttemp": "14",
                    "daywind": "南",
                    "nightwind": "南",
                    "daypower": "4",
                    "nightpower": "4"
                },
                {
                    "date": "2020-10-16",
                    "week": "5",
                    "dayweather": "小雨",
                    "nightweather": "阴",
                    "daytemp": "20",
                    "nighttemp": "10",
                    "daywind": "西北",
                    "nightwind": "西北",
                    "daypower": "4",
                    "nightpower": "4"
                }
            ]
        }
    ]
}

```
### 获取 xml 格式返回值
 第三个参数值为返回值类型，可选`xml` 和 `json`，默认 `json`
```php
$response = $weather->getWeather('深圳', 'all', 'xml');

```
### 示例
```xml
<response>
    <status>1</status>
    <count>1</count>
    <info>OK</info>
    <infocode>10000</infocode>
    <lives type="list">
        <live>
            <province>广东</province>
            <city>深圳市</city>
            <adcode>440300</adcode>
            <weather>中雨</weather>
            <temperature>27</temperature>
            <winddirection>西南</winddirection>
            <windpower>5</windpower>
            <humidity>94</humidity>
            <reporttime>2018-08-21 16:00:00</reporttime>
        </live>
    </lives>
</response>

```
### 参数说明
```php
array|string getWeather(string $city, string $type='base', string $format = 'json')
```
- `$city` - 城市名，比如：“深圳”;
- `$type` - 返回内容类型： `base` :返回实况天气/ `all`:返回预报天气；
- `$formate` - 输出的数据格式，默认为 `json`格式，当 output 设置为“xml”时，输出的为 XML 格式的数据

## 在 `Laravel`中使用
在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php`中:
```php
    .
    .
    .
    'weather' => [
         'key' => env('WEATHER_API_KEY'),
    ],   

```
然后再 `.env` 中配置 `WEATHER_API_KEY` :
```php
WEATHER_API_KEY=XXXXXXXXXXXXXXXXXXXXX
```
可以用两种方式获取 `Xin6841414\Weather\Weather` 实例：
### 方法参数注入
```php
    .
    .
    .
    public function edit(Weather $weather)
    {
        $response = $weather->getWeather('深圳');
    }
    
```
### 服务名访问
```php

    .
    .
    .
    public function edit() 
    {
        $response = app('weather')->getWeather('深圳');
    }
    .
    .
    .
```
## 参考
- [高德开放平台天气接口](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)
## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/xin6841414/weather/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/xin6841414/weather/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT