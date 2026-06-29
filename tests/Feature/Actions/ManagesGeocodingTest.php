<?php

namespace Foutraz\Weather\Tests\Feature\Actions;

use Foutraz\Weather\Dto\Place;
use Foutraz\Weather\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesGeocodingTest extends TestCase
{
    #[Test]
    public function it_searches_places_by_name(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                ['name' => 'Lyon', 'state' => 'Auvergne-Rhône-Alpes', 'country' => 'FR', 'lat' => 45.7589, 'lon' => 4.8414],
                ['name' => 'Lyon', 'country' => 'US', 'lat' => 42.4759, 'lon' => -71.6884],
            ]),
        ]);

        $places = $manager->geocoding()->search('Lyon');

        $this->assertCount(2, $places);
        $this->assertInstanceOf(Place::class, $places[0]);
        $this->assertSame('Lyon, Auvergne-Rhône-Alpes, FR', $places[0]->label());
        $this->assertStringContainsString('geo/1.0/direct', $this->lastRequestUri());
        $this->assertStringContainsString('q=Lyon', $this->lastRequestUri());
    }

    #[Test]
    public function it_returns_an_empty_array_when_no_place_matches(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, []),
        ]);

        $this->assertSame([], $manager->geocoding()->search('Zzzzzz'));
    }

    #[Test]
    public function it_reverse_geocodes_coordinates(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                ['name' => 'Paris', 'state' => 'Île-de-France', 'country' => 'FR', 'lat' => 48.8566, 'lon' => 2.3522],
            ]),
        ]);

        $place = $manager->geocoding()->reverse(48.8566, 2.3522);

        $this->assertInstanceOf(Place::class, $place);
        $this->assertSame('Paris, Île-de-France, FR', $place->label());
        $this->assertStringContainsString('geo/1.0/reverse', $this->lastRequestUri());
    }

    #[Test]
    public function it_returns_null_when_reverse_geocoding_finds_nothing(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, []),
        ]);

        $this->assertNull($manager->geocoding()->reverse(0.0, 0.0));
    }
}
