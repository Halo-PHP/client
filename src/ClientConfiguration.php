<?php

namespace Halo;

use Halo\Http\Authentication\Authenticator;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final readonly class ClientConfiguration
{
    public Authenticator $authenticator;
    public string $baseUri;
    public ?ClientInterface $httpClient;
    public ?StreamFactoryInterface $streamFactory;
    public ?RequestFactoryInterface $requestFactory;

    public function __construct(
        Authenticator $authenticator,
        string $baseUri,
        ?ClientInterface $httpClient = null,
        ?StreamFactoryInterface  $streamFactory = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) {
        $this->authenticator = $authenticator;
        $this->baseUri = rtrim($baseUri, '/');
        $this->httpClient = $httpClient;
        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
    }
}