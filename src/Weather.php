<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 9-30 030
 * Time: 8:52
 */

namespace Xin6841414\Weather;


use GuzzleHttp\Client;

use Xin6841414\Weather\Exceptions\HttpException;
use Xin6841414\Weather\Exceptions\InvalidArgumentException;

class Weather
{

    protected $key;
    protected $guzzleOptions = [];

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @param $city 城市名/高德地址位置adcode，比如：“深圳” 或者（adcode：440300）;
     * @url https://lbs.amap.com/api/webservice/guide/api/district
     * @param string $type 返回内容类型： base :返回实况天气/all: 返回预报天气；
     * @param string $format 输出的数据格式，默认为 json 格式， 当 output 设置为 “xml” 时，输出的为 xml 格式的数据
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWeather($city, $type = 'base', $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }

        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): ' . $type);
        }

        $query = array_filter([
           'key' => $this->key,
           'city' => $city,
           'output' => $format,
           'extensions' => $type,
        ]);
        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();
            return 'json' === $format ? \json_decode($response, true) : $response;
        }catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLiveWeather($city, $format = 'json')
    {
        return $this->getWeather($city, 'base', $format);
    }

    public function getForecastsWeather($city, $format = 'json')
    {
        return $this->getWeather($city, 'all', $format);
    }
}