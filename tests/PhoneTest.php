<?php

class MockPhoneTest
{
    use Validate;

    public function getRules()
    {
        return [
            "phone" => "phone"
        ];
    }
}

class PhoneTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidPhone()
    {
        $input = [
            "phone" => "1-234-567-8901"
        ];
        $mockPhone = new MockPhoneTest();
        $this->assertEquals(true, $mockPhone->check($input));
    }

    public function testRejectInvalidPhone()
    {
        $input = [
            "phone" => "1-234"
        ];
        $mockPhone = new MockPhoneTest();
        $this->assertContains("The value of phone must be a valid phone number", $mockPhone->check($input));
    }
}