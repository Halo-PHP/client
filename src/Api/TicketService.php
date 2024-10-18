<?php

namespace Halo\Api;

use Halo\Client;
use Halo\Http\Method;

final readonly class TicketService
{
    public function __construct(private Client $client)
    {}

    public function list(array $query = []): array
    {
        return $this->client->send(
            method: Method::GET,
            path: 'api/tickets',
            query: $query
        );
    }

    public function get(int $id, array $query = []): array
    {
        return $this->client->send(
            method: Method::GET,
            path: 'api/tickets/' . $id,
            query: $query
        );
    }
}