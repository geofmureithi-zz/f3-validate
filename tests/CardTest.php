<?php

class MockCardTest
{
    use Validate;

    public function getRules()
    {
        return [
            "ccard" => "card"
        ];
    }
}

class CardTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidCard()
    {
        $input = [
            "ccard" => "343760667618602"
        ];
        $mockCard = new MockCardTest();
        $this->assertEquals(true, $mockCard->check($input));
    }

    public function testRejectInvalidCard()
    {
        $input = [
            "ccard" => "test"
        ];
        $mockCard = new MockCardTest();
        $this->assertContains("The value of ccard must be a valid credit card", $mockCard->check($input));
    }
}