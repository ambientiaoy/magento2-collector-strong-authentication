<?php

namespace Ambientia\CollectorStrongAuthentication\Test\Unit\Model;

use PHPUnit\Framework\TestCase;

class JWTDecoderTest extends TestCase
{
    private $jwtDecoder;

    protected function setUp()
    {
        $this->jwtDecoder = new \Ambientia\CollectorStrongAuthentication\Model\JWTDecoder();
    }

    /**
     * @test
     */
    public function shouldHaveNationalId()
    {
        $token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IkFEMTQ0MzU1NDRGNzVEMzY4MzY3NkZGRkNCNEQyMjZBREJFRDVGMkUiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2lkcC11YXQuY29sbGVjdG9yYmFuay5zZSIsInN1YiI6ImpUdXhPRFl2NWNOclJrNFRnSWRJRXlFNExfSmUwUnZMeEQyVnhGS0pJeWciLCJhdWQiOiJ5V3N6OWtYN25lc1ZINHRWMUhpMCIsImF1dGhfdGltZSI6MTU4MTU3NzMwMCwibmF0aW9uYWxfaWQiOiIwMTAyMDBBOTYxOCIsIm5hdGlvbmFsX2lkcCI6ImZ0biIsIm5hdGlvbmFsX2NvdW50cnkiOiJGSSIsImFjciI6InVybjpjb2xsZWN0b3JiYW5rOmFjOm1ldGhvZDpmdG4iLCJuYW1lIjoiT25uaSBKdWhhbmkgS29yaG9uZW4iLCJnaXZlbl9uYW1lIjoiT25uaSBKdWhhbmkiLCJmYW1pbHlfbmFtZSI6IktvcmhvbmVuIiwiaWF0IjoxNTgxNTc3MzA2LCJleHAiOjE1ODE1NzczMzZ9.BJ2F6aQILq8gKXRV8AmcME2R5ia79jtalE0zRx9wv97pFTu-UX0vZMsKfuZkOyB667NQvVrA019VQ7bavTJmURf2og8RBD6dvXDwatOJP5dHN_XmIgLZh0ge3O2fsL7G3P31Zvb1NUD3PyxnRqklbDd9ZX2Vb9acDRMFbluV09JiBpsWPh3gIIMfWgVWPeAsHWlX902mmb7k_wsdOYOXOEdEyoKCVqImBGBjwxudPgcsQNGqjxdNan_VIy1PfVUQUU3_zkzT63X4kazqP2vz3c8ma2xuMWiuKbLnLmEb9Zx-YRHCHkEJrgsA4FX3CfHiUnkuC4prnJRLmpYklYTzkg';
        $result = $this->jwtDecoder->decodeWithoutPrivateKey($token);
        $this->assertEquals('010200A9618', $result['national_id']);
    }
}
