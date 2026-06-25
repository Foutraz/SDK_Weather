<?php

namespace Foutraz\Weather\Tests\Unit\Dto;

use Foutraz\Weather\Dto\ForecastEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ForecastEntryTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $entry = ForecastEntry::fromArray([
            'dt' => 1714003200,
            'main' => ['temp' => 15.2, 'feels_like' => 14.8],
            'weather' => [['main' => 'Clouds', 'description' => 'scattered clouds']],
            'clouds' => ['all' => 40],
            'wind' => ['speed' => 4.2, 'gust' => 7.6],
            'visibility' => 10000,
            'pop' => 0.32,
            'rain' => ['3h' => 1.2],
        ]);

        $this->assertSame(15.2, $entry->temp);
        $this->assertSame(14.8, $entry->feelsLike);
        $this->assertSame(4.2, $entry->windSpeed);
        $this->assertSame(7.6, $entry->windGust);
        $this->assertSame(0.32, $entry->pop);
        $this->assertSame(1.2, $entry->rain3h);
        $this->assertSame(40, $entry->cloudiness);
        $this->assertSame(10000, $entry->visibility);
        $this->assertSame('Clouds', $entry->weatherMain);
        $this->assertSame('scattered clouds', $entry->description);
        $this->assertSame(1714003200, $entry->dt->getTimestamp());
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $entry = ForecastEntry::fromArray([
            'dt' => 1714003200,
            'main' => ['temp' => 10.0, 'feels_like' => 9.0],
            'wind' => ['speed' => 2.0],
            'clouds' => ['all' => 10],
        ]);

        $this->assertNull($entry->windGust);
        $this->assertNull($entry->rain3h);
        $this->assertNull($entry->visibility);
        $this->assertSame(0.0, $entry->pop);
        $this->assertSame('', $entry->weatherMain);
    }
}
