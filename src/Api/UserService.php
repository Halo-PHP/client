<?php

namespace Halo\Api;

use Halo\Client;
use Halo\Http\Method;

final readonly class UserService
{
    public function __construct(private Client $client)
    {}

    public function list(array $query = []): array
    {
        return $this->client->send(
            method: Method::GET,
            path: 'api/users',
            query: $query,
        );
    }
}