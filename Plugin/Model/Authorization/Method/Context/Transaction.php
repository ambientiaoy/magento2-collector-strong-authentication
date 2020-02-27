<?php


namespace Ambientia\CollectorStrongAuthentication\Plugin\Model\Authorization\Method\Context;


use Ambientia\CollectorStrongAuthentication\Model\Config;
use Customweb_Collector_Constant_Form;

class Transaction
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Transaction constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Customweb\CollectorCw\Model\Authorization\Method\Context\Transaction $subject
     * @param array $parameters
     * @return array
     */
    public function afterGetParameters(
        \Customweb\CollectorCw\Model\Authorization\Method\Context\Transaction $subject,
        array $parameters
    ) {
        if (!$this->config->getIsActive()) {
            return $parameters;
        }
        $order = $subject->getOrder();
        if ($order && $order->getId()) {
            if ($payment = $order->getPayment()) {
                if ($additionalInformation = $payment->getAdditionalInformation()) {
                    foreach ($additionalInformation as $key => $value) {
                        $parameters[$key] = $value;
                    }
                }
            }
        }
        return $parameters;
    }
}
