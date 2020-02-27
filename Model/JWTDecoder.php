<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


class JWTDecoder
{
    /**
     * @param string $jwt
     * @return array
     */
    public function decodeWithoutPrivateKey(string $jwt): array
    {
        list($header, $payload, $signature) = explode (".", $jwt);
        return json_decode(base64_decode($payload), true);
    }
}
