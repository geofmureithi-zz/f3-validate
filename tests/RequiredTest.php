<?php

class MockRequiredTest
{
    use Validate;

    public function getRules()
    {
        return [
            "email" => "required"
        ];
    }
}

class RequiredTest extends \PHPUnit\Framework\TestCase
{

    public function testAcceptWhenExists()
    {
        $input = [
            "email" => "test@email.com"
        ];
        $mock = new MockRequiredTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenEmpty()
    {
        $input = [
            "email" => ""
        ];
        $mock = new MockRequiredTest();
        $this->assertContains("The value of email is required", $mock->check($input));
    }

    public function testRejectWhenNonExistent()
    {
        $input = [
            "website" => "google.com"
        ];
        $mock = new MockRequiredTest();
        $this->assertContains("The value of email is required", $mock->check($input));
    }
}