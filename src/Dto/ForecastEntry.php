<?php

namespace Foutraz\Weather\Dto;

use DateTimeImmutable;

class ForecastEntry
{
    public function __construct(
        public DateTimeImmutable $dt,
        public float $temp,
        public float $feelsLike,
        public float $windSpeed,
        public ?float $windGust,
        public float $pop,
        public ?float $rain3h,
        public int $cloudiness,
        public ?int $visibility,
        public string $weatherMain,
        public string $description,
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
            (float) ($data['wind']['speed'] ?? 0.0),
            isset($data['wind']['gust']) ? (float) $data['wind']['gust'] : null,
            (float) ($data['pop'] ?? 0.0),
            isset($data['rain']['3h']) ? (float) $data['rain']['3h'] : null,
            (int) ($data['clouds']['all'] ?? 0),
            isset($data['visibility']) ? (int) $data['visibility'] : null,
            (string) ($weather['main'] ?? ''),
            (string) ($weather['description'] ?? ''),
        );
    }
}
