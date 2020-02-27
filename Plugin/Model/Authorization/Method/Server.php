<?php


namespace Ambientia\CollectorStrongAuthentication\Plugin\Model\Authorization\Method;


use Ambientia\CollectorStrongAuthentication\Model\Config;
use Customweb_Collector_Constant_Form;

class Server
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Server constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Customweb\CollectorCw\Model\Authorization\Method\Server $subject
     * @param array $formFields
     * @return array
     */
    public function afterGetVisibleFormFields(\Customweb\CollectorCw\Model\Authorization\Method\Server $subject, array $formFields)
    {
        if (!$this->config->getIsActive()) {
            return $formFields;
        }
        foreach ($formFields as $key => $formField) {
            if ($control = $formField->getControl()) {
                if ($control->getControlName() == Customweb_Collector_Constant_Form::SSN) {
                    unset($formFields[$key]);
                }
            }
        }
        return $formFields;
    }
}
