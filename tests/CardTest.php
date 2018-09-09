<?php
require_once('src/Validate.php');

class MockCardTest{
    use Validate;
    public  function getRules()
    {
        return [
            "ccard" => "card"
        ];
    }
}

class CardTest extends \PHPUnit\Framework\TestCase {

    public function testAssertValidEmail(){
        $input = [
            "ccard" => "343760667618602"
        ];
        $mockEmail = new MockCardTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectInvalidEmail(){
        $input = [
            "ccard" => "test"
        ];
        $mockEmail = new MockCardTest();
        $this->assertContains("The value of ccard must be a valid credit card", $mockEmail->check($input));
    }
}