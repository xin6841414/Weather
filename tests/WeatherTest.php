<?php
namespace Xin6841414\Weather\Tests;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;
use Xin6841414\Weather\Exceptions\HttpException;
use Xin6841414\Weather\Exceptions\InvalidArgumentException;
use Xin6841414\Weather\Weather;

/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-9 009
 * Time: 10:21
 */

class WeatherTest extends Testcase
{
    public function testGetWeather()
    {
        $response = new Response(200, [], '{"success": true}');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'output' => 'json',
                'extensions' => 'base',
            ]
        ])->andReturn($response);
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);
        $this->assertSame(['success' => true], $w->getWeather('深圳'));

        $response =  new Response(200, [], '<hello>content</hello>');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'extensions' => 'all',
                'output' => 'xml'
            ],
        ])->andReturn($response);

        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('<hello>content</hello>', $w->getWeather('深圳', 'all', 'xml'));
    }

    public function testGetHttpClient()
    {
        $w = new Weather('mock-key');
        $this->assertInstanceOf(ClientInterface::class, $w->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $w = new Weather('mock-key');
        $this->assertNull($w->getHttpClient()->getConfig('timeout'));
        $w->setGuzzleOptions(['timeout' => 5000]);
        $this->assertSame(5000, $w->getHttpClient()->getConfig('timeout'));
    }

    public function testGetWeatherWithInvalidType()
    {
        $w = new Weather('497fde1f7b8c22daf1730065dc97d93b');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type value(base/all): foo');
        $w->getWeather('深圳', 'foo');
        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather('497fde1f7b8c22daf1730065dc97d93b');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid response format: array');
        $w->getWeather('深圳','base', 'array');
        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->get(new AnyArgs())
            ->andThrow(new \Exception('request timeout'));
        $w = \Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');
        $w->getWeather('深圳');
    }
}