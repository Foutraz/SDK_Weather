<?php

namespace Foutraz\Weather\Actions;

use Foutraz\Weather\Dto\Forecast;
use Foutraz\Weather\Exceptions\ActionFailed;
use Foutraz\Weather\Exceptions\InvalidData;
use Foutraz\Weather\Exceptions\ResourceNotFound;
use Foutraz\Weather\Exceptions\TooManyRequestsException;
use Foutraz\Weather\Exceptions\Unauthorized;
use Foutraz\Weather\WeatherManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesForecast extends WeatherManager
{
    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function at(float $lat, float $lon): Forecast
    {
        return Forecast::fromArray($this->get('data/2.5/forecast', ['lat' => $lat, 'lon' => $lon]));
    }
}
