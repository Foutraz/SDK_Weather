<?php

namespace Foutraz\Weather\Dto;

class Place
{
    public function __construct(
        public string $name,
        public ?string $state,
        public string $country,
        public float $lat,
        public float $lon,
    ) {}

    /**
     * Builds a place from an OpenWeatherMap geocoding entry.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['name'] ?? ''),
            isset($data['state']) ? (string) $data['state'] : null,
            (string) ($data['country'] ?? ''),
            (float) ($data['lat'] ?? 0.0),
            (float) ($data['lon'] ?? 0.0),
        );
    }

    /**
     * Renders a human-readable label from the non-empty location parts.
     */
    public function label(): string
    {
        return implode(', ', array_filter([$this->name, $this->state, $this->country]));
    }
}
