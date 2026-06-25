# foutraz/weather

A framework-agnostic PHP SDK for the [OpenWeatherMap API](https://openweathermap.org/api), with first-class Laravel integration (ServiceProvider + Facade). Built on Guzzle, it returns typed DTOs, maps HTTP errors to named exceptions, and appends your API key and metric units to every request.

## Requirements

- PHP `^8.4`
- `guzzlehttp/guzzle` `^7.0`
- `illuminate/support` `^11.0|^12.0` (Laravel integration only)

## Installation

```bash
composer require foutraz/weather
```

## Environment variables

| Variable | Description | Default |
| --- | --- | --- |
| `OPENWEATHER_API_KEY` | OpenWeatherMap API key | — |
| `OPENWEATHER_BASE_URL` | API base endpoint | `https://api.openweathermap.org` |

In Laravel, the `WeatherServiceProvider` is auto-discovered and binds `WeatherManager` as a singleton resolvable via the `Weather` facade. Every request automatically carries `appid` (your key) and `units=metric`.

## Building a manager

### Laravel

```php
use Foutraz\Weather\WeatherManager;

$weather = app(WeatherManager::class);
```

### Standalone

```php
use Foutraz\Weather\WeatherManager;

$weather = new WeatherManager(
    endpoint: 'https://api.openweathermap.org',
    apiKey: $apiKey,
);
```

## Current weather

```php
$current = $weather->currentWeather()->at(48.8534, 2.3488); // CurrentWeather

$current->temp;        // °C
$current->feelsLike;   // °C
$current->humidity;    // %
$current->pressure;    // hPa
$current->windSpeed;   // m/s
$current->windGust;    // ?float
$current->cloudiness;  // %
$current->visibility;  // ?int (metres)
$current->rain1h;      // ?float (mm)
$current->weatherMain; // e.g. "Rain"
$current->description; // e.g. "light rain"
$current->dt;          // DateTimeImmutable
$current->lat;
$current->lon;
```

## Forecast (5-day / 3-hour)

```php
$forecast = $weather->forecast()->at(48.8534, 2.3488); // Forecast

$forecast->lat;
$forecast->lon;

foreach ($forecast->entries as $entry) { // array<ForecastEntry>
    $entry->dt;          // DateTimeImmutable
    $entry->temp;        // °C
    $entry->feelsLike;   // °C
    $entry->windSpeed;   // m/s
    $entry->windGust;    // ?float
    $entry->pop;         // precipitation probability 0–1
    $entry->rain3h;      // ?float (mm)
    $entry->cloudiness;  // %
    $entry->visibility;  // ?int (metres)
    $entry->weatherMain;
    $entry->description;
}
```

## DTOs

All DTOs live under `Foutraz\Weather\Dto` and expose a static `fromArray(array): self` factory mapping OpenWeatherMap payloads to typed properties: `CurrentWeather`, `ForecastEntry`, and `Forecast` (with `collectionFromArray` mapping the OWM `list`).

## Error handling

HTTP errors are mapped to named exceptions under `Foutraz\Weather\Exceptions`:

| Status | Exception |
| --- | --- |
| 401 | `Unauthorized` |
| 404 | `ResourceNotFound` |
| 422 | `InvalidData` |
| 429 | `TooManyRequestsException` |
| other 4xx/5xx | `ActionFailed` |

## Testing

```bash
composer test
```
