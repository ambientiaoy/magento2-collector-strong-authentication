<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


use Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterface;
use Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterfaceFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class TokenResponseBuilder implements \Ambientia\CollectorStrongAuthentication\Api\TokenResponseBuilderInterface
{
    /**
     * @var TokenInterfaceFactory
     */
    private $tokenInterfaceFactory;
    /**
     * @var JWTDecoder
     */
    private $JWTDecoder;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TokenResponseBuilder constructor.
     * @param TokenInterfaceFactory $tokenInterfaceFactory
     * @param JWTDecoder $JWTDecoder
     * @param LoggerInterface $logger
     */
    public function __construct(TokenInterfaceFactory $tokenInterfaceFactory, JWTDecoder $JWTDecoder, LoggerInterface $logger)
    {
        $this->tokenInterfaceFactory = $tokenInterfaceFactory;
        $this->JWTDecoder = $JWTDecoder;
        $this->logger = $logger;
    }

    /**
     * @param ResponseInterface $response
     * @return TokenInterface
     */
    public function createFromResponse(ResponseInterface $response): TokenInterface
    {
        /** @var TokenInterface $token */
        $token = $this->tokenInterfaceFactory->create();
        $responseData = json_decode($response->getBody());
        $responseToken = $responseData->id_token;
        $result = $this->JWTDecoder->decodeWithoutPrivateKey($responseToken);
        $this->logger->debug('Decoded Token Response Data: ' . json_encode($result));
        $token->setData($result);
        return $token;
    }
}
