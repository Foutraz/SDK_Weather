<?php

namespace Foutraz\Weather\Actions;

use Foutraz\Weather\Dto\CurrentWeather;
use Foutraz\Weather\Exceptions\ActionFailed;
use Foutraz\Weather\Exceptions\InvalidData;
use Foutraz\Weather\Exceptions\ResourceNotFound;
use Foutraz\Weather\Exceptions\TooManyRequestsException;
use Foutraz\Weather\Exceptions\Unauthorized;
use Foutraz\Weather\WeatherManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesCurrentWeather extends WeatherManager
{
    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function at(float $lat, float $lon): CurrentWeather
    {
        return CurrentWeather::fromArray($this->get('data/2.5/weather', ['lat' => $lat, 'lon' => $lon]));
    }
}
