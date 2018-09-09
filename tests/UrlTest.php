<?php
require_once('src/Validate.php');

class MockUrlTest{
    use Validate;
    public function getRules()
    {
        return [
            "website" => "url"
        ];
    }
}

class UrlTest extends \PHPUnit\Framework\TestCase {

    public function testAcceptWhenExists(){
        $input = [
            "website" => "www.google.com"
        ];
        $mockEmail = new MockUrlTest();
        $this->assertEquals(true, $mockEmail->check($input));
    }

    public function testRejectWhenNonExistOrInvalid(){
        $input = [
            "website" => ""
        ];
        $mockEmail = new MockUrlTest();
        $this->assertContains("The value of email is required", $mockEmail->check($input));
    }
}