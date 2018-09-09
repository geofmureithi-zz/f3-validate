<?php


class MockMiscTest
{
    use Validate;

    public function getRules()
    {
        return [
            "boolean" => "boolean",
            "password" => "matches,repeat_password",
            "regex" => "regex,/(\\d+)/"
        ];
    }
}

class MiscTest extends \PHPUnit\Framework\TestCase
{

    public function testAcceptOnValidBoolean()
    {
        $input = [
            "boolean" => "on"
        ];
        $mock = new MockMiscTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenBooleanInvalid()
    {
        $input = [
            "boolean" => "nada"
        ];
        $mock = new MockMiscTest();
        $this->assertContains("The value of boolean can only contain boolean values", $mock->check($input));
    }

    public function testAcceptOnValidMatches()
    {
        $input = [
            "password" => "&s#Nk;SJn",
            "repeat_password" => "&s#Nk;SJn",
        ];
        $mock = new MockMiscTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectOnMismatches()
    {
        $input = [
            "password" => "&s#Nk;SJn",
            "repeat_password" => "123456",
        ];
        $mock = new MockMiscTest();
        $this->assertContains("The value of password should match that of repeat_password", $mock->check($input));
    }

    public function testAcceptOnValidRegexMatch()
    {
        $input = [
            "regex" => "100"
        ];
        $mock = new MockMiscTest();
        $this->assertEquals(true, $mock->check($input));
    }

    public function testRejectWhenRegexMatchFailed()
    {
        $input = [
            "regex" => "nada"
        ];
        $mock = new MockMiscTest();
        $this->assertContains("The value of regex must match the regex /(\\d+)/", $mock->check($input));
    }
}