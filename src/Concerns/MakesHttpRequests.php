<?php

namespace Foutraz\Weather\Concerns;

use Foutraz\Weather\Exceptions\ActionFailed;
use Foutraz\Weather\Exceptions\InvalidData;
use Foutraz\Weather\Exceptions\ResourceNotFound;
use Foutraz\Weather\Exceptions\TooManyRequestsException;
use Foutraz\Weather\Exceptions\Unauthorized;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

trait MakesHttpRequests
{
    /**
     * @param  array<string, mixed>  $query
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function get(string $uri, array $query = []): mixed
    {
        return $this->request('GET', $uri, [], $query);
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     */
    public function post(string $uri, array $payload = []): mixed
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $query
     *
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     * @throws TooManyRequestsException
     */
    public function request(string $verb, string $uri, array $payload = [], array $query = []): mixed
    {
        $options = [];

        if (! empty($payload)) {
            $options['json'] = $payload;
        }

        $options['query'] = array_merge($this->defaultQuery(), $query);

        $response = $this->client->request($verb, $uri, $options);

        if (! $this->isSuccessful($response)) {
            $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        $decoded = json_decode($responseBody, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $responseBody;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'appid' => $this->apiKey,
            'units' => 'metric',
        ];
    }

    public function isSuccessful(?ResponseInterface $response): bool
    {
        if (! $response) {
            return false;
        }

        return (int) substr((string) $response->getStatusCode(), 0, 1) === 2;
    }

    /**
     * @throws ActionFailed
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    protected function handleRequestError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 422) {
            throw new InvalidData(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() === 404) {
            throw new ResourceNotFound;
        }

        if ($response->getStatusCode() === 429) {
            throw new TooManyRequestsException((string) $response->getBody());
        }

        if ($response->getStatusCode() === 401) {
            throw new Unauthorized((string) $response->getBody());
        }

        throw new ActionFailed((string) $response->getBody());
    }
}
