<?php


namespace Ambientia\CollectorStrongAuthentication\Plugin\Model\Payment\Method;


use Ambientia\CollectorStrongAuthentication\Model\Config;
use Customweb_Collector_Constant_Form;

class CollectorDirect
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;
    /**
     * @var Config
     */
    private $config;

    /**
     * CollectorDirect constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param Config $config
     */
    public function __construct(\Magento\Checkout\Model\Session $checkoutSession, Config $config)
    {
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
    }

    /**
     * @param \Customweb\CollectorCw\Model\Payment\Method\CollectorDirect $subject
     * @param callable $proceed
     * @return bool
     */
    public function aroundValidate(\Customweb\CollectorCw\Model\Payment\Method\CollectorDirect $subject, callable $proceed)
    {
        if (!$this->config->getIsActive()) {
            return $proceed();
        }
        $quote = $this->checkoutSession->getQuote();
        if ($quote->getPayment()->getAdditionalInformation(Customweb_Collector_Constant_Form::CONDITION_CHECKBOX)) {
            return true;
        }
        return $proceed();
    }
}
