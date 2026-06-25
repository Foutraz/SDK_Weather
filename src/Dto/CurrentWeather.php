<?php

namespace Foutraz\Weather\Dto;

use DateTimeImmutable;

class CurrentWeather
{
    public function __construct(
        public DateTimeImmutable $dt,
        public float $temp,
        public float $feelsLike,
        public int $humidity,
        public int $pressure,
        public float $windSpeed,
        public ?float $windGust,
        public int $cloudiness,
        public ?int $visibility,
        public ?float $rain1h,
        public string $weatherMain,
        public string $description,
        public float $lat,
        public float $lon,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $weather = $data['weather'][0] ?? [];

        return new self(
            (new DateTimeImmutable)->setTimestamp((int) ($data['dt'] ?? 0)),
            (float) ($data['main']['temp'] ?? 0.0),
            (float) ($data['main']['feels_like'] ?? 0.0),
            (int) ($data['main']['humidity'] ?? 0),
            (int) ($data['main']['pressure'] ?? 0),
            (float) ($data['wind']['speed'] ?? 0.0),
            isset($data['wind']['gust']) ? (float) $data['wind']['gust'] : null,
            (int) ($data['clouds']['all'] ?? 0),
            isset($data['visibility']) ? (int) $data['visibility'] : null,
            isset($data['rain']['1h']) ? (float) $data['rain']['1h'] : null,
            (string) ($weather['main'] ?? ''),
            (string) ($weather['description'] ?? ''),
            (float) ($data['coord']['lat'] ?? 0.0),
            (float) ($data['coord']['lon'] ?? 0.0),
        );
    }
}
