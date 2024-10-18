<?php

namespace Halo\Http\Authentication;

use Halo\ClientConfiguration;
use Psr\Http\Message\RequestInterface;

interface Authenticator
{
    public function authenticate(RequestInterface $request, ClientConfiguration $config): RequestInterface;
}