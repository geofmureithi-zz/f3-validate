<?php


class MockTraitTest
{
    use Validate;

    public function getRules()
    {
        return [
            "phone" => "phone"
        ];
    }
}

class TraitTest extends \PHPUnit\Framework\TestCase
{

    public function testAssertValidTrait()
    {
        $mockTrait = new MockTraitTest();
        $this->assertTrue(is_callable(array($mockTrait, 'addValidator')));
    }
}