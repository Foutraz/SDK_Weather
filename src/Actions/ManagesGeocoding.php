<?php

namespace Foutraz\Weather\Actions;

use Foutraz\Weather\Dto\Place;
use Foutraz\Weather\Exceptions\ActionFailed;
use Foutraz\Weather\Exceptions\InvalidData;
use Foutraz\Weather\Exceptions\ResourceNotFound;
use Foutraz\Weather\Exceptions\TooManyRequestsException;
use Foutraz\Weather\Exceptions\Unauthorized;
use Foutraz\Weather\WeatherManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesGeocoding extends WeatherManager
{
    /**
     * Searches geocoded places matching the given name.
     *
     * @return array<int, Place>
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function search(string $query, int $limit = 5): array
    {
        $results = $this->get('geo/1.0/direct', ['q' => $query, 'limit' => $limit]);

        return array_map(static fn (array $place): Place => Place::fromArray($place), $results);
    }

    /**
     * Resolves the closest place for the given coordinates.
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function reverse(float $lat, float $lon): ?Place
    {
        $results = $this->get('geo/1.0/reverse', ['lat' => $lat, 'lon' => $lon, 'limit' => 1]);

        return isset($results[0]) ? Place::fromArray($results[0]) : null;
    }
}
