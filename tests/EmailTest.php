<?php

class MockEmailTest
{
    use Validate;

    public function getRules()
    {
        return [
            "email" => "email"
        ];
    }
}

class EmailTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidEmail()
    {
        $input = [
            "email" => "test@email.com"
        ];
        $mockEmail = new MockEmailTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectInvalidEmail()
    {
        $input = [
            "email" => "test"
        ];
        $mockEmail = new MockEmailTest();
        $this->assertContains("The value of email must be a valid email", $mockEmail->check($input));
    }
}