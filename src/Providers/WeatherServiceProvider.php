<?php

namespace Foutraz\Weather\Providers;

use Foutraz\Weather\WeatherManager;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class WeatherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/weather.php',
            'weather'
        );

        $this->app->singleton(WeatherManager::class, function ($app) {

            $config = $app['config']['weather'];

            if (blank($config['endpoint'])) {
                throw new RuntimeException(
                    'No OpenWeatherMap API endpoint was provided.'
                );
            }

            return new WeatherManager(
                $config['endpoint'],
                $config['api_key'],
            );
        });

        $this->app->alias(WeatherManager::class, 'weather');
    }

    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__, 2).'/config/weather.php' =>
                $this->app->configPath('weather.php'),
        ], 'weather-config');
    }
}
