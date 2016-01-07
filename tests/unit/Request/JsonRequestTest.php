<?php

namespace Rhubarb\Crown\Tests\unit\unit\Request;

use Rhubarb\Crown\Context;
use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;

class JsonRequestTest extends RhubarbTestCase
{
    /**
     * @var Context
     */
    private $context;

    protected function setUp()
    {
        parent::setUp();

        $this->context = new Context();
        $this->context->Request = null;
        $this->context->SimulateNonCli = true;

        $_SERVER["CONTENT_TYPE"] = "application/json";
    }

    public function testPayload()
    {
        $testPayload =
            [ "a" => 1,
              "b" => 2
            ];

        $this->context->SimulatedRequestBody = json_encode($testPayload);

        $request = Context::currentRequest();

        $this->assertEquals($testPayload, $request->getPayload());
    }
}