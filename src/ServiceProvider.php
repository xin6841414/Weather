<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-10 010
 * Time: 17:01
 */

namespace Xin6841414\Weather;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function(){
            return new Weather(config('services.weather.key'));
        });
        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}