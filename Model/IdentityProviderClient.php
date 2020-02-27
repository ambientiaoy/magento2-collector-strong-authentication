<?php


namespace Ambientia\CollectorStrongAuthentication\Model;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class IdentityProviderClient
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var URIResolver
     */
    private $URIResolver;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * IdentityProviderClient constructor.
     * @param Client $client
     * @param Config $config
     * @param URIResolver $URIResolver
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, Config $config, URIResolver $URIResolver, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->config = $config;
        $this->URIResolver = $URIResolver;
        $this->logger = $logger;
    }

    /**
     * @param string $code
     * @return ResponseInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function requestToken(string $code): ResponseInterface
    {
        $response = $this->client->post(
            $this->URIResolver->getTokenURI(),
            [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded'],
                'form_params' =>
                    [
                        'client_id' => $this->config->getClientId(),
                        'client_secret' => $this->config->getClientSecret(),
                        'grant_type' => 'authorization_code',
                        'code' => $code,
                        'redirect_uri' => $this->URIResolver->getCallbackURI()
                    ]
            ]
        );
        $this->logger->debug('Request Token Response: ' . $response->getBody());
        return $response;
    }
}
