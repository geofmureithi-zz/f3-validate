<?php
require_once('src/Validate.php');

class MockRequiredTest{
    use Validate;
    public function getRules()
    {
        return [
            "email" => "required"
        ];
    }
}

class RequiredTest extends \PHPUnit\Framework\TestCase {

    public function testAcceptWhenExists(){
        $input = [
            "email" => "test@email.com"
        ];
        $mockEmail = new MockRequiredTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectWhenEmpty(){
        $input = [
            "email" => ""
        ];
        $mockEmail = new MockRequiredTest();
        $this->assertContains("The value of email is required", $mockEmail->check($input));
    }

    public function testRejectWhenNonExistent(){
        $input = [
            "website" => "google.com"
        ];
        $mockEmail = new MockRequiredTest();
        $this->assertContains("The value of email is required", $mockEmail->check($input));
    }
}