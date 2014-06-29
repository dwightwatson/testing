<?php

use \Mockery;

class TestingTraitTest extends \PHPUnit_Framework_TestCase {

    public $trait;

    public function setUp()
    {
        $this->trait = new TestingTraitStub;
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testTraitUsable()
    {
        $traits = class_uses($this->trait);

        $this->assertContains('Watson\Testing\TestingTrait', $traits);
    }

    public function testIncludesControllerTrait()
    {
        $this->assertTrue(method_exists($this->trait, 'assertViewIs'));
    }

    public function testIncludesModelTrait()
    {
        $this->assertTrue(method_exists($this->trait, 'assertValid'));
    }

}

class TestingTraitStub {

    use \Watson\Testing\TestingTrait;

}