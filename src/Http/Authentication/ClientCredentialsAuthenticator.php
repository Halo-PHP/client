<?php

namespace Halo\Http\Authentication;

use Halo\ClientConfiguration;
use Psr\Http\Message\RequestInterface;

final readonly class ClientCredentialsAuthenticator implements Authenticator
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private array $scopes = ['all'],
        private ?string $tenant = null,
    ) {}

    public function authenticate(RequestInterface $request, ClientConfiguration $config): RequestInterface
    {
        $query = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => implode(',', $this->scopes),
        ];

        if (isset($this->tenant)) {
            $query['tenant'] = $this->tenant;
        }

        $authRequest = $config->requestFactory->createRequest('POST', $config->baseUri . '/auth/token');
        $authRequest = $authRequest->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $authRequest = $authRequest->withBody(
            $config->streamFactory->createStream(http_build_query($query))
        );

        $response = $config->httpClient->sendRequest($authRequest);
        $accessToken = json_decode($response->getBody(), true)['access_token'];

        return $request->withHeader('Authorization', "Bearer {$accessToken}");
    }
}