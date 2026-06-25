<?php

namespace Foutraz\Weather\Tests\Unit\Dto;

use Foutraz\Weather\Dto\Forecast;
use Foutraz\Weather\Dto\ForecastEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    #[Test]
    public function it_maps_the_list_into_entries(): void
    {
        $forecast = Forecast::fromArray([
            'city' => ['coord' => ['lat' => 48.8534, 'lon' => 2.3488]],
            'list' => [
                ['dt' => 1714003200, 'main' => ['temp' => 15.2, 'feels_like' => 14.8], 'wind' => ['speed' => 4.2], 'clouds' => ['all' => 40], 'pop' => 0.1, 'weather' => [['main' => 'Clouds', 'description' => 'few clouds']]],
                ['dt' => 1714014000, 'main' => ['temp' => 13.1, 'feels_like' => 12.5], 'wind' => ['speed' => 3.8], 'clouds' => ['all' => 80], 'pop' => 0.6, 'weather' => [['main' => 'Rain', 'description' => 'moderate rain']]],
            ],
        ]);

        $this->assertSame(48.8534, $forecast->lat);
        $this->assertSame(2.3488, $forecast->lon);
        $this->assertCount(2, $forecast->entries);
        $this->assertContainsOnlyInstancesOf(ForecastEntry::class, $forecast->entries);
        $this->assertSame(15.2, $forecast->entries[0]->temp);
        $this->assertSame('Rain', $forecast->entries[1]->weatherMain);
    }

    #[Test]
    public function it_defaults_to_an_empty_list(): void
    {
        $forecast = Forecast::fromArray([]);

        $this->assertSame(0.0, $forecast->lat);
        $this->assertSame(0.0, $forecast->lon);
        $this->assertCount(0, $forecast->entries);
    }
}
