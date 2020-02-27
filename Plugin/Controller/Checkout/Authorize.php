<?php


namespace Ambientia\CollectorStrongAuthentication\Plugin\Controller\Checkout;

use Ambientia\CollectorStrongAuthentication\Model\Config;
use Ambientia\CollectorStrongAuthentication\Model\SSNResolver;
use Ambientia\CollectorStrongAuthentication\Model\URIResolver;
use Customweb_Collector_Constant_Form;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class Authorize
{

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var SSNResolver
     */
    private $SSNResolver;
    /**
     * @var URIResolver
     */
    private $URIResolver;
    /**
     * @var Config
     */
    private $config;

    /**
     * Authorize constructor.
     * @param RedirectFactory $redirectFactory
     * @param CheckoutSession $checkoutSession
     * @param OrderRepositoryInterface $orderRepository
     * @param SSNResolver $SSNResolver
     * @param URIResolver $URIResolver
     * @param Config $config
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        CheckoutSession $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        SSNResolver $SSNResolver,
        URIResolver $URIResolver,
        Config $config
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->SSNResolver = $SSNResolver;
        $this->URIResolver = $URIResolver;
        $this->config = $config;
    }

    /**
     * @param \Customweb\CollectorCw\Controller\Checkout\Authorize $subject
     * @param callable $proceed
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundExecute(\Customweb\CollectorCw\Controller\Checkout\Authorize $subject, callable $proceed)
    {
        if (!$this->config->getIsActive()) {
            return $proceed();
        }
        $order = $this->checkoutSession->getLastRealOrder();
        if ($ssn = $this->SSNResolver->getCustomerSSNFromSession()) {
            $order->getPayment()->setAdditionalInformation(Customweb_Collector_Constant_Form::SSN, $ssn);
            $this->orderRepository->save($order);
        }
        if ($order->getPayment()->getAdditionalInformation(Customweb_Collector_Constant_Form::SSN)) {
            return $proceed();
        }
        $redirect = $this->redirectFactory->create();
        return $redirect->setUrl($this->URIResolver->getRedirectURI());
    }
}
