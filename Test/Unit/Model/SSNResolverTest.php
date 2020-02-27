<?php

namespace Ambientia\CollectorStrongAuthentication\Test\Unit\Model;

use Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterface;
use Ambientia\CollectorStrongAuthentication\Api\TokenResponseBuilderInterface;
use Ambientia\CollectorStrongAuthentication\Model\IdentityProviderClient;
use Ambientia\CollectorStrongAuthentication\Model\SSNResolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class SSNResolverTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
    }

    /**
     * @test
     */
    public function shouldGetSsnFromIdentityProvider()
    {
        $expectedSSN = 'identity-provider-ssn';

        $client = $this->getClientMock();

        $tokenResponseBuilderMock = $this->getTokenResponseBuilderMock($expectedSSN);

        /** @var SSNResolver $SSNResolver */
        $SSNResolver = $this->objectManager->getObject(SSNResolver::class, ['client' => $client, 'tokenResponseBuilder' => $tokenResponseBuilderMock]);
        $ssn = $SSNResolver->getCustomerSSNFromIdentityProvider('code');
        $this->assertEquals($expectedSSN, $ssn);
    }

    /**
     * @test
     */
    public function shouldGetSsnFromCustomerSession()
    {
        $expectedSSN = 'saved-customer-ssn';

        $sessionMock = $this->getCustomerSessionMock($expectedSSN, true);

        /** @var SSNResolver $SSNResolver */
        $SSNResolver = $this->objectManager->getObject(SSNResolver::class, ['session' => $sessionMock]);
        $ssn = $SSNResolver->getCustomerSSNFromSession();
        $this->assertEquals($expectedSSN, $ssn);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getClientMock(): \PHPUnit_Framework_MockObject_MockObject
    {
        $responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $client = $this->getMockBuilder(IdentityProviderClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestToken'])
            ->getMock();
        $client->method('requestToken')->willReturn($responseMock);
        return $client;
    }

    /**
     * @param string $expectedSSN
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getTokenResponseBuilderMock(string $expectedSSN): \PHPUnit_Framework_MockObject_MockObject
    {
        $token = $this->getMockBuilder(TokenInterface::class)
            ->setMethods(['getNationalId'])
            ->getMockForAbstractClass();
        $token->method('getNationalId')->willReturn($expectedSSN);

        $tokenResponseBuilderMock = $this->getMockBuilder(TokenResponseBuilderInterface::class)
            ->setMethods(['createFromResponse'])
            ->getMock();
        $tokenResponseBuilderMock->method('createFromResponse')
            ->willReturn($token);
        return $tokenResponseBuilderMock;
    }

    /**
     * @param string $expectedSSN
     * @param bool $isLoggedIn
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getCustomerSessionMock(string $expectedSSN, bool $isLoggedIn): \PHPUnit_Framework_MockObject_MockObject
    {
        $customerData = $this->getMockBuilder(\Magento\Customer\Model\Data\Customer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSsn'])
            ->getMock();
        $customerData->method('getSsn')
            ->willReturn($expectedSSN);

        $sessionMock = $this->getMockBuilder(\Magento\Customer\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCustomer', 'isLoggedIn'])
            ->getMock();
        $sessionMock->method('getCustomer')
            ->willReturn($customerData);
        $sessionMock->method('isLoggedIn')
            ->willReturn($isLoggedIn);
        return $sessionMock;
    }
}
