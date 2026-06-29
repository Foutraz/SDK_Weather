<?php

namespace Foutraz\Weather;

use Foutraz\Weather\Actions\ManagesCurrentWeather;
use Foutraz\Weather\Actions\ManagesForecast;
use Foutraz\Weather\Actions\ManagesGeocoding;
use Foutraz\Weather\Concerns\MakesHttpRequests;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class WeatherManager
{
    use MakesHttpRequests;

    public function __construct(
        public string $endpoint,
        public string $apiKey,
        public ?ClientInterface $client = null
    ) {
        $this->client ??= new Client([
            'http_errors' => false,
            'base_uri' => rtrim($this->endpoint, '/').'/',
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function currentWeather(): ManagesCurrentWeather
    {
        return new ManagesCurrentWeather($this->endpoint, $this->apiKey, $this->client);
    }

    public function forecast(): ManagesForecast
    {
        return new ManagesForecast($this->endpoint, $this->apiKey, $this->client);
    }

    public function geocoding(): ManagesGeocoding
    {
        return new ManagesGeocoding($this->endpoint, $this->apiKey, $this->client);
    }
}
