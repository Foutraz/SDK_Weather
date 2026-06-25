<?php

namespace Foutraz\Weather\Tests\Unit\Dto;

use Foutraz\Weather\Dto\CurrentWeather;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CurrentWeatherTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $weather = CurrentWeather::fromArray([
            'coord' => ['lon' => 2.3488, 'lat' => 48.8534],
            'weather' => [['main' => 'Rain', 'description' => 'light rain']],
            'main' => ['temp' => 12.4, 'feels_like' => 11.1, 'pressure' => 1012, 'humidity' => 87],
            'visibility' => 10000,
            'wind' => ['speed' => 5.1, 'gust' => 9.3],
            'clouds' => ['all' => 75],
            'rain' => ['1h' => 0.5],
            'dt' => 1714000000,
        ]);

        $this->assertSame(48.8534, $weather->lat);
        $this->assertSame(2.3488, $weather->lon);
        $this->assertSame(12.4, $weather->temp);
        $this->assertSame(11.1, $weather->feelsLike);
        $this->assertSame(87, $weather->humidity);
        $this->assertSame(1012, $weather->pressure);
        $this->assertSame(5.1, $weather->windSpeed);
        $this->assertSame(9.3, $weather->windGust);
        $this->assertSame(75, $weather->cloudiness);
        $this->assertSame(10000, $weather->visibility);
        $this->assertSame(0.5, $weather->rain1h);
        $this->assertSame('Rain', $weather->weatherMain);
        $this->assertSame('light rain', $weather->description);
        $this->assertSame(1714000000, $weather->dt->getTimestamp());
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $weather = CurrentWeather::fromArray([
            'main' => ['temp' => 20.0, 'feels_like' => 19.5, 'pressure' => 1000, 'humidity' => 50],
            'wind' => ['speed' => 3.0],
            'clouds' => ['all' => 0],
            'dt' => 1714000000,
        ]);

        $this->assertNull($weather->windGust);
        $this->assertNull($weather->visibility);
        $this->assertNull($weather->rain1h);
        $this->assertSame('', $weather->weatherMain);
        $this->assertSame(0.0, $weather->lat);
    }
}
