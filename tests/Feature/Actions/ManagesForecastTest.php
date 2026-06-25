<?php

namespace Foutraz\Weather\Tests\Feature\Actions;

use Foutraz\Weather\Dto\Forecast;
use Foutraz\Weather\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesForecastTest extends TestCase
{
    #[Test]
    public function it_returns_the_forecast_at_coordinates(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'city' => ['coord' => ['lat' => 48.8534, 'lon' => 2.3488]],
                'list' => [
                    ['dt' => 1714003200, 'main' => ['temp' => 15.2, 'feels_like' => 14.8], 'wind' => ['speed' => 4.2], 'clouds' => ['all' => 40], 'pop' => 0.2, 'weather' => [['main' => 'Clouds', 'description' => 'few clouds']]],
                    ['dt' => 1714014000, 'main' => ['temp' => 12.9, 'feels_like' => 12.1], 'wind' => ['speed' => 3.1], 'clouds' => ['all' => 75], 'pop' => 0.7, 'rain' => ['3h' => 2.1], 'weather' => [['main' => 'Rain', 'description' => 'light rain']]],
                ],
            ]),
        ]);

        $forecast = $manager->forecast()->at(48.8534, 2.3488);

        $this->assertInstanceOf(Forecast::class, $forecast);
        $this->assertCount(2, $forecast->entries);
        $this->assertSame(0.7, $forecast->entries[1]->pop);
        $this->assertSame(2.1, $forecast->entries[1]->rain3h);
        $this->assertStringContainsString('data/2.5/forecast', $this->lastRequestUri());
    }

    #[Test]
    public function it_returns_an_empty_forecast_when_the_list_is_empty(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, ['city' => ['coord' => ['lat' => 0.0, 'lon' => 0.0]], 'list' => []]),
        ]);

        $forecast = $manager->forecast()->at(0.0, 0.0);

        $this->assertCount(0, $forecast->entries);
    }
}
