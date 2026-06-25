<?php

namespace Foutraz\Weather\Dto;

class Forecast
{
    /**
     * @param  array<int, ForecastEntry>  $entries
     */
    public function __construct(
        public float $lat,
        public float $lon,
        public array $entries,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $list
     * @return array<int, ForecastEntry>
     */
    public static function collectionFromArray(array $list): array
    {
        return array_map(static fn (array $entry): ForecastEntry => ForecastEntry::fromArray($entry), $list);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (float) ($data['city']['coord']['lat'] ?? 0.0),
            (float) ($data['city']['coord']['lon'] ?? 0.0),
            self::collectionFromArray($data['list'] ?? []),
        );
    }
}
