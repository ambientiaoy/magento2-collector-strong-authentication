<?php


namespace Ambientia\CollectorStrongAuthentication\Controller\Callback;

use Ambientia\CollectorStrongAuthentication\Model\Config;
use Ambientia\CollectorStrongAuthentication\Model\SSNResolver;
use Customweb_Collector_Constant_Form;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class Index extends Action
{
    /**
     * @var RedirectInterface
     */
    private $redirect;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var SSNResolver
     */
    private $SSNResolver;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Index constructor.
     * @param Context $context
     * @param RedirectInterface $redirect
     * @param Session $session
     * @param OrderRepositoryInterface $orderRepository
     * @param SSNResolver $SSNResolver
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        RedirectInterface $redirect,
        Session $session,
        OrderRepositoryInterface $orderRepository,
        SSNResolver $SSNResolver,
        Config $config,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->redirect = $redirect;
        $this->session = $session;
        $this->orderRepository = $orderRepository;
        $this->SSNResolver = $SSNResolver;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if ($this->config->getIsActive()) {
                $code = $this->getRequest()->getParam('code');
                $ssn = $this->SSNResolver->getCustomerSSNFromIdentityProvider($code);
                $order = $this->session->getLastRealOrder();
                $order->getPayment()->setAdditionalInformation(Customweb_Collector_Constant_Form::SSN, $ssn);
                $this->orderRepository->save($order);
            }
            $this->redirect->redirect($this->getResponse(), 'collectorcw/checkout/authorize');
        } catch (\Exception $exception) {
            $this->logger->addCritical($exception);
            $this->session->restoreQuote();
            $this->messageManager->addErrorMessage(__('There was a problem on the authentication. Please try again.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
    }
}
