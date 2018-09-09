<?php

class MockEmailMxTest
{
    use Validate;

    public function getRules()
    {
        return [
            "email" => "email,true"
        ];
    }
}

class EmailMxTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidEmail()
    {
        $input = [
            "email" => "test@gmail.com"
        ];
        $mockEmail = new MockEmailTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectValidEmailWithIncorrectMx()
    {
        $input = [
            "email" => "test@email.lq"
        ];
        $mockEmail = new MockEmailMxTest();
        $this->assertContains("The value of email must be a valid email", $mockEmail->check($input));
    }

    public function testRejectInvalidEmailWithMx()
    {
        $input = [
            "email" => "test"
        ];
        $mockEmail = new MockEmailMxTest();
        $this->assertContains("The value of email must be a valid email", $mockEmail->check($input));
    }
}