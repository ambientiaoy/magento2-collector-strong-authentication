<?php


namespace Ambientia\CollectorStrongAuthentication\Model\Logger\Handler;


use Ambientia\CollectorStrongAuthentication\Model\Config;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base as BaseHandler;

class Debug extends BaseHandler
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Debug constructor.
     * @param DriverInterface $filesystem
     * @param null $filePath
     * @param null $fileName
     * @param Config $config
     */
    public function __construct(DriverInterface $filesystem, $filePath = null, $fileName = null, Config $config)
    {
        parent::__construct($filesystem, $filePath, $fileName);
        $this->config = $config;
    }

    /** @var int $loggerType */
    protected $loggerType = \Monolog\Logger::DEBUG;

    /**
     * @param array $record
     */
    public function write(array $record)
    {
        if ($this->config->getDebug()) {
            parent::write($record);
        }
    }
}
