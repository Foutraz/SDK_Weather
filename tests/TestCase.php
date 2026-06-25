<?php

namespace Foutraz\Weather\Tests;

use Foutraz\Weather\WeatherManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $history = [];

    /**
     * Builds a WeatherManager whose Guzzle client replays the queued responses.
     *
     * @param  array<int, Response>  $responses
     */
    protected function managerWithResponses(array $responses): WeatherManager
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));

        $client = new Client([
            'handler' => $stack,
            'http_errors' => false,
            'base_uri' => 'https://api.openweathermap.org/',
        ]);

        return new WeatherManager(
            'https://api.openweathermap.org',
            'test-api-key',
            $client,
        );
    }

    /**
     * Builds a JSON-bodied Guzzle response.
     *
     * @param  array<mixed>  $body
     */
    protected function jsonResponse(int $status, array $body): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], (string) json_encode($body));
    }

    protected function lastRequestUri(): string
    {
        $request = end($this->history)['request'];

        return (string) $request->getUri();
    }
}
