<?php

namespace Ambientia\CollectorStrongAuthentication\Test\Unit\Model;

use Ambientia\CollectorStrongAuthentication\Model\Config;
use Ambientia\CollectorStrongAuthentication\Model\URIResolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class URIResolverTest extends TestCase
{
    private $objectManager;
    /**
     * @var string
     */
    private $clientURI;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $callbackURI;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->clientURI = 'https://idp-uat.collectorbank.se/';
        $this->clientId = 'test-client-id';
        $this->callbackURI = 'https://www.example.com/collectorauth/callback';
    }

    /**
     * @test
     */
    public function shouldGetRedirectUri()
    {
        $callbackURI = 'https://www.example.com/collectorauth/callback';
        $storeManagerMock = $this->getStoreManagerMock();
        $config = $this->getConfigMock();
        $expectedURI = "https://idp-uat.collectorbank.se/authorize?scope=openid&client_id=$this->clientId&response_type=code&redirect_uri=$this->callbackURI&acr_values=urn%3Acollectorbank%3Aac%3Amethod%3Aftn";
        /** @var URIResolver $URIResolver */
        $URIResolver = $this->objectManager->getObject(URIResolver::class, ['config' => $config, 'storeManager' => $storeManagerMock]);
        $this->assertEquals($expectedURI, $URIResolver->getRedirectURI());
    }

    /**
     * @test
     */
    public function shouldGetTokenURI()
    {
        $expectedURI = "https://idp-uat.collectorbank.se/token";
        $config = $this->getConfigMock();
        /** @var URIResolver $URIResolver */
        $URIResolver = $this->objectManager->getObject(URIResolver::class, ['config' => $config]);
        $this->assertEquals($expectedURI, $URIResolver->getTokenURI());
    }

    /**
     * @test
     */
    public function shouldGetCallbackURI()
    {
        $config = $this->getConfigMock();
        $storeManagerMock = $this->getStoreManagerMock();
        /** @var URIResolver $URIResolver */
        $URIResolver = $this->objectManager->getObject(URIResolver::class, ['config' => $config, 'storeManager' => $storeManagerMock]);
        $this->assertEquals($this->callbackURI, $URIResolver->getCallbackURI());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getStoreManagerMock(): \PHPUnit_Framework_MockObject_MockObject
    {
        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->setMethods(['getBaseUrl'])
            ->getMockForAbstractClass();
        $storeMock->method('getBaseUrl')->willReturn('https://www.example.com/');
        $storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->setMethods(['getStore'])
            ->getMockForAbstractClass();
        $storeManagerMock->method('getStore')
            ->willReturn($storeMock);
        return $storeManagerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfigMock(): \PHPUnit_Framework_MockObject_MockObject
    {
        $config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClientId', 'getClientURI'])
            ->getMock();

        $config->method('getClientURI')->willReturn($this->clientURI);
        $config->method('getClientId')->willReturn($this->clientId);
        return $config;
    }
}
