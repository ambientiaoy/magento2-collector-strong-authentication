<?php

namespace Ambientia\CollectorStrongAuthentication\Test\Unit\Model;

use Ambientia\CollectorStrongAuthentication\Model\TokenResponseBuilder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class TokenResponseBuilderTest extends TestCase
{
    /** @var TokenResponseBuilder $tokenResponseBuilder */
    private $tokenResponseBuilder;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $token = $objectManager->getObject(\Ambientia\CollectorStrongAuthentication\Model\Token::class);
        $JWTDecoder = $objectManager->getObject(\Ambientia\CollectorStrongAuthentication\Model\JWTDecoder::class);
        $tokenInterfaceFactory = $this->getMockBuilder(\Ambientia\CollectorStrongAuthentication\Api\Data\TokenInterfaceFactory::class)
            ->setMethods(['create'])
            ->getMock();
        $tokenInterfaceFactory->method('create')
            ->willReturn($token);
        $this->tokenResponseBuilder = $objectManager->getObject(
            TokenResponseBuilder::class,
            [
                'tokenInterfaceFactory' => $tokenInterfaceFactory,
                'JWTDecoder' => $JWTDecoder
            ]
        );
    }

    /**
     * @test
     */
    public function testCreateFromResponse()
    {
        $expectedNationalId = '010200A9618';
        $body = '{"access_token":"f9qqklFrSblsUvEJfPgX_g","token_type":"Bearer","id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6IkFEMTQ0MzU1NDRGNzVEMzY4MzY3NkZGRkNCNEQyMjZBREJFRDVGMkUiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2lkcC11YXQuY29sbGVjdG9yYmFuay5zZSIsInN1YiI6ImpUdXhPRFl2NWNOclJrNFRnSWRJRXlFNExfSmUwUnZMeEQyVnhGS0pJeWciLCJhdWQiOiJ5V3N6OWtYN25lc1ZINHRWMUhpMCIsImF1dGhfdGltZSI6MTU4MTU5MTc0NSwibmF0aW9uYWxfaWQiOiIwMTAyMDBBOTYxOCIsIm5hdGlvbmFsX2lkcCI6ImZ0biIsIm5hdGlvbmFsX2NvdW50cnkiOiJGSSIsImFjciI6InVybjpjb2xsZWN0b3JiYW5rOmFjOm1ldGhvZDpmdG4iLCJuYW1lIjoiT25uaSBKdWhhbmkgS29yaG9uZW4iLCJnaXZlbl9uYW1lIjoiT25uaSBKdWhhbmkiLCJmYW1pbHlfbmFtZSI6IktvcmhvbmVuIiwiaWF0IjoxNTgxNTkxNzU4LCJleHAiOjE1ODE1OTE3ODh9.fN-9fOwFlarrL01k5CXT8UwkowsivWx5Z6zaQfXdZWwKNwznzklRLe08VSTsHvvfGVjqBB8rE9wVIMc9We8qVjJEc9IRELCgHnlBDXAhP4V5jBKEkGr1zl63MOTDt6RhTQ1MiwGyyCg5nEw557EX-uVfzvlTC4aNcszql2QDriz1_rXTJTKsFo9DYNvFLNBzjmmkHT5yOz0v5WoXv8oiTTygeZPu2tJnpQYz-yHgA1n3NtDvungp86s_V9jCsVUXHCtHso2vneoK9vYcplxfKC76Y84raFuQDsDPKTlU40gGGkhdqLErVvEZ9i1v7hlL-TwpUbGgwNkEwxSpS7I50g","scope":"openid"}';
        $responseMock = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)
            ->setMethods(['getBody'])
            ->getMockForAbstractClass();

        $responseMock->method('getBody')->willReturn($body);

        $token = $this->tokenResponseBuilder->createFromResponse($responseMock);
        $this->assertEquals($expectedNationalId, $token->getNationalId());
    }
}
