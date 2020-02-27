<?php

namespace Ambientia\CollectorStrongAuthentication\Test\Unit\Model;

use Ambientia\CollectorStrongAuthentication\Model\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    private $token;

    protected function setUp()
    {
        $this->token = new Token();
    }

    /**
     * @test
     */
    public function shouldGetNationalId()
    {
        $nationalId = '010200A9618';
        $this->token->setData(['national_id' => $nationalId]);
        $this->assertEquals($nationalId, $this->token->getNationalId());
    }
}
