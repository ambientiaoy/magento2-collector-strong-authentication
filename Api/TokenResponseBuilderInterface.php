<?php


namespace Ambientia\CollectorStrongAuthentication\Api;


use Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterface;
use Psr\Http\Message\ResponseInterface;

interface TokenResponseBuilderInterface
{
    public function createFromResponse(ResponseInterface $response): TokenInterface;
}
