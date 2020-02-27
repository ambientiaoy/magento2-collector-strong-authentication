<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class URIResolver
{
    const CALLBACK_URI = 'collectorauth/callback';
    const TOKEN_URI = 'token';

    /**
     * @var Config
     */
    private $config;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * URIResolver constructor.
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(Config $config, StoreManagerInterface $storeManager, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRedirectURI(): string
    {
        $uri = $this->config->getClientURI()
            . 'authorize?scope=openid&client_id='
            . $this->config->getClientId()
            . '&response_type=code&redirect_uri='
            . $this->getCallbackURI()
            . '&acr_values=urn%3Acollectorbank%3Aac%3Amethod%3Aftn';
        $this->logger->debug('Redirect URI: ' . $uri);
        return $uri;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCallbackURI(): string
    {
        $uri = $this->storeManager->getStore()->getBaseUrl() . self::CALLBACK_URI;
        $this->logger->debug('Callback URI: ' . $uri);
        return $uri;
    }

    /**
     * @return string
     */
    public function getTokenURI(): string
    {
        $uri = $this->config->getClientURI() . self::TOKEN_URI;
        $this->logger->debug('Token URI: ' . $uri);
        return $uri;
    }
}
