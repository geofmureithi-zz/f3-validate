<?php

class MockExtendTest
{
    use Validate;

    public function getRules()
    {
        return [
            "fruits" => "contains,(apples&bananas)"
        ];
    }
}

class ExtendTest extends \PHPUnit\Framework\TestCase
{
    public function testAssertValidExtend()
    {
        $input = [
            "fruits" => "berries,apples,bananas,mangoes"
        ];
        $mockExtend = new MockExtendTest();
        $this->assertEquals(true, $mockExtend->check($input));
    }

    public function testRejectInvalidExtend()
    {
        $input = [
            "fruits" => "1-234"
        ];
        $mockExtend = new MockExtendTest();
        $this->assertContains("The value of fruits must include each of these items : (apples&bananas)", $mockExtend->check($input));
    }

    protected function setUp()
    {
        //You must do this somewhere in your code before validating
        $message = "The value of {0} must include each of these items : {1}";
        Validate::addValidator("contains", function ($value, $ruleConfigs) {
            $required = explode("&", substr($ruleConfigs[0], 1, -1));
            $diff = array_diff($required, explode(",", $value));
            return empty($diff);
        }, $message);
    }
}