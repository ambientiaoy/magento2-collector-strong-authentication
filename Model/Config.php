<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const CONFIG_PATH = 'collectorcw/strong_authentication';
    const CONFIG_KEY_ACTIVE = 'active';
    const CONFIG_KEY_DEBUG = 'debug';
    const CONFIG_KEY_CLIENT_ID = 'client_id';
    const CONFIG_KEY_CLIENT_SECRET = 'client_secret';
    const CONFIG_KEY_CLIENT_URI = 'client_uri';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $config
     */
    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->config->getValue(self::CONFIG_PATH . "/" . self::CONFIG_KEY_ACTIVE);
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->config->getValue(self::CONFIG_PATH . "/" . self::CONFIG_KEY_DEBUG);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        $key = $this->getKeyPrefix() . self::CONFIG_KEY_CLIENT_ID;
        return $this->config->getValue(self::CONFIG_PATH . "/" . $key);
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        $key = $this->getKeyPrefix() . self::CONFIG_KEY_CLIENT_SECRET;
        return $this->config->getValue(self::CONFIG_PATH . "/" . $key);
    }

    /**
     * @return mixed
     */
    public function getClientURI()
    {
        $key = $this->getKeyPrefix() . self::CONFIG_KEY_CLIENT_URI;
        return $this->config->getValue(self::CONFIG_PATH . "/" . $key);
    }

    private function getKeyPrefix()
    {
        return $this->config->getValue('collectorcw/general/operating_mode') . '_';
    }
}
