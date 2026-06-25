<?php

namespace Foutraz\Weather\Tests\Unit\Concerns;

use Foutraz\Weather\Exceptions\ActionFailed;
use Foutraz\Weather\Exceptions\InvalidData;
use Foutraz\Weather\Exceptions\ResourceNotFound;
use Foutraz\Weather\Exceptions\TooManyRequestsException;
use Foutraz\Weather\Exceptions\Unauthorized;
use Foutraz\Weather\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MakesHttpRequestsTest extends TestCase
{
    #[Test]
    public function it_maps_401_to_unauthorized(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(401, ['message' => 'Invalid API key'])]);

        $this->expectException(Unauthorized::class);

        $manager->currentWeather()->at(48.85, 2.35);
    }

    #[Test]
    public function it_maps_404_to_resource_not_found(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(404, [])]);

        $this->expectException(ResourceNotFound::class);

        $manager->currentWeather()->at(48.85, 2.35);
    }

    #[Test]
    public function it_maps_422_to_invalid_data(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(422, ['errors' => ['lat']])]);

        $this->expectException(InvalidData::class);

        $manager->currentWeather()->at(48.85, 2.35);
    }

    #[Test]
    public function it_maps_400_to_action_failed(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(400, ['message' => 'bad request'])]);

        $this->expectException(ActionFailed::class);

        $manager->currentWeather()->at(48.85, 2.35);
    }

    #[Test]
    public function it_maps_429_to_too_many_requests(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(429, ['message' => 'rate limited'])]);

        $this->expectException(TooManyRequestsException::class);

        $manager->currentWeather()->at(48.85, 2.35);
    }

    #[Test]
    public function it_appends_appid_and_units_on_every_request(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, ['dt' => 1714000000, 'main' => ['temp' => 12.0, 'feels_like' => 11.0, 'pressure' => 1010, 'humidity' => 80], 'wind' => ['speed' => 3.0], 'clouds' => ['all' => 0], 'coord' => ['lat' => 48.85, 'lon' => 2.35], 'weather' => [['main' => 'Clear', 'description' => 'clear sky']]]),
        ]);

        $manager->currentWeather()->at(48.85, 2.35);

        $uri = $this->lastRequestUri();
        $this->assertStringContainsString('appid=test-api-key', $uri);
        $this->assertStringContainsString('units=metric', $uri);
        $this->assertStringContainsString('lat=48.85', $uri);
        $this->assertStringContainsString('lon=2.35', $uri);
    }
}
