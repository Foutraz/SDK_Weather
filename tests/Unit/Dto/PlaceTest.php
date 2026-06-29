<?php

namespace Foutraz\Weather\Tests\Unit\Dto;

use Foutraz\Weather\Dto\Place;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PlaceTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $place = Place::fromArray([
            'name' => 'Lyon',
            'state' => 'Auvergne-Rhône-Alpes',
            'country' => 'FR',
            'lat' => 45.7589,
            'lon' => 4.8414,
        ]);

        $this->assertSame('Lyon', $place->name);
        $this->assertSame('Auvergne-Rhône-Alpes', $place->state);
        $this->assertSame('FR', $place->country);
        $this->assertSame(45.7589, $place->lat);
        $this->assertSame(4.8414, $place->lon);
        $this->assertSame('Lyon, Auvergne-Rhône-Alpes, FR', $place->label());
    }

    #[Test]
    public function it_omits_a_missing_state_from_the_label(): void
    {
        $place = Place::fromArray([
            'name' => 'Singapore',
            'country' => 'SG',
            'lat' => 1.2897,
            'lon' => 103.8501,
        ]);

        $this->assertNull($place->state);
        $this->assertSame('Singapore, SG', $place->label());
    }
}
