<?php

namespace Foutraz\Weather\Tests\Feature\Actions;

use Foutraz\Weather\Dto\CurrentWeather;
use Foutraz\Weather\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesCurrentWeatherTest extends TestCase
{
    #[Test]
    public function it_returns_the_current_weather_at_coordinates(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'coord' => ['lon' => 2.3488, 'lat' => 48.8534],
                'weather' => [['main' => 'Clouds', 'description' => 'overcast clouds']],
                'main' => ['temp' => 14.6, 'feels_like' => 13.9, 'pressure' => 1015, 'humidity' => 72],
                'visibility' => 10000,
                'wind' => ['speed' => 4.6],
                'clouds' => ['all' => 90],
                'dt' => 1714002000,
            ]),
        ]);

        $weather = $manager->currentWeather()->at(48.8534, 2.3488);

        $this->assertInstanceOf(CurrentWeather::class, $weather);
        $this->assertSame(14.6, $weather->temp);
        $this->assertSame('Clouds', $weather->weatherMain);
        $this->assertStringContainsString('data/2.5/weather', $this->lastRequestUri());
    }
}
