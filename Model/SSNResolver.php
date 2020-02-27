<?php


namespace Ambientia\CollectorStrongAuthentication\Model;


use Ambientia\CollectorStrongAuthentication\Api\TokenResponseBuilderInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;

class SSNResolver
{
    /**
     * @var IdentityProviderClient
     */
    private $client;
    /**
     * @var TokenResponseBuilderInterface
     */
    private $tokenResponseBuilder;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * SSNResolver constructor.
     * @param IdentityProviderClient $client
     * @param TokenResponseBuilderInterface $tokenResponseBuilder
     * @param Session $session
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        IdentityProviderClient $client,
        TokenResponseBuilderInterface $tokenResponseBuilder,
        Session $session,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->client = $client;
        $this->tokenResponseBuilder = $tokenResponseBuilder;
        $this->session = $session;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param string $code
     * @return string|null
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function getCustomerSSNFromIdentityProvider(string $code): ?string
    {
        $response = $this->client->requestToken($code);
        $token = $this->tokenResponseBuilder->createFromResponse($response);
        $ssn = $token->getNationalId();
        if ($ssn && $this->session->isLoggedIn()) {
            $customer = $this->session->getCustomer()->getDataModel();
            $customer->setCustomAttribute('ssn', $ssn);
            $this->customerRepository->save($customer);
        }
        return $ssn;
    }

    /**
     * @return string|null
     */
    public function getCustomerSSNFromSession(): ?string
    {
        if (!$this->session->isLoggedIn()) {
            return null;
        }
        return $this->session->getCustomer()->getSsn();
    }
}
