<?php

class MockIPTest
{
    use Validate;

    public function getRules()
    {
        return [
            "ip1" => "ipv4",
            "ip2" => "ipv6",
        ];
    }
}

class IPTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidIps()
    {
        $input = [
            "ip1" => "178.7.35.202",
            "ip2" => "2001:db8::1428:57ab"
        ];
        $mockIp = new MockIPTest();
        $this->assertEquals(true, $mockIp->check($input));
    }

    public function testRejectInvalidIps()
    {
        $input = [
            "ip1" => "locallost",
            "ip2" => "10000.1.1.0.1"
        ];
        $mockIp = new MockIPTest();
        $this->assertContains("The value of ip1 must be a valid ipv4 address", $mockIp->check($input));
        $this->assertContains("The value of ip2 must be a valid ipv6 address", $mockIp->check($input));
    }
}