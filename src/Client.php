<?php

namespace Halo;

use Halo\Api\TicketService;
use Halo\Api\UserService;
use Halo\Http\Method;

final readonly class Client
{
    public TicketService $tickets;
    public UserService $users;

    private ClientConfiguration $config;

    public function __construct(ClientConfiguration $config)
    {
        $this->config = $config;

        $this->tickets = new TicketService($this);
        $this->users = new UserService($this);
    }

    public function send(Method $method, string $path, array $headers = [], array $query = [], ?string $body = null): array
    {
        array_walk_recursive($query, function (&$value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
        });

        $uri = $this->config->baseUri . '/' . ltrim($path, '/') . '?' . http_build_query(data: $query);
        $request = $this->config->requestFactory->createRequest($method->value, $uri);

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withHeader('Accept', 'application/json');

        if ($body !== null) {
            $request = $request->withBody(
                $this->config->streamFactory->createStream($body)
            );
        }

        $request = $this->config->authenticator->authenticate($request, $this->config);
        $response = $this->config->httpClient->sendRequest($request);

        return json_decode($response->getBody(), true) ?? [];
    }
}