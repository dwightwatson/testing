<?php

use \Illuminate\Support\Facades\View;

class ControllerHelpersTest extends \PHPUnit_Framework_TestCase {

    use Watson\Testing\ControllerHelpers;

    public $controller;

    public $response;

    public function setUp()
    {
        $this->controller = new ControllerStub;
        $this->response = Mockery::mock('Illuminate\Foundation\Testing\Client');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testAssertViewIs()
    {
        View::shouldReceive('make')
            ->once()
            ->with('foo');

        $this->response->shouldReceive('getOriginalContent')
            ->once()
            ->andReturn(Mockery::mock([
                'getName' => 'foo'
            ]));

        $this->controller->show();

        $this->assertViewis('foo');
    }

}

class ControllerStub {

    public function show()
    {
        return View::make('foo');
    }

}