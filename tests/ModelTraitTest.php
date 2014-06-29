<?php

class ModelTraitTest extends \PHPUnit_Framework_TestCase {

    use \Watson\Testing\ModelHelpers;

    public $model;

    public function setUp()
    {
        $this->model = Mockery::mock('EloquentStub');
    }

    public function testAssertValid()
    {
        $this->model->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->assertValid($this->model);
    }

    public function testAssertInvalid()
    {
        $this->model->shouldReceive('isInvalid')
            ->once()
            ->andReturn(true);

        $this->assertInvalid($this->model);
    }

    public function testAssertValidWith()
    {
        $this->model->shouldReceive('setAttribute')
            ->once()
            ->with('foo', 'bar');

        $this->model->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->assertValidWith($this->model, 'foo', 'bar');
    }

    public function testAssertValidWithout()
    {
        $this->model->shouldReceive('setAttribute')
            ->once()
            ->with('foo', null);

        $this->model->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $this->assertValidWithout($this->model, 'foo');
    }

    public function testAssertInvalidWith()
    {
        $this->model->shouldReceive('setAttribute')
            ->once()
            ->with('foo', 'bar');

        $this->model->shouldReceive('isInvalid')
            ->once()
            ->andReturn(true);

        $this->assertInvalidWith($this->model, 'foo', 'bar');
    }

    public function tesetAssertInvalidWithout()
    {
        $this->model->shouldReceive('setAttribute')
            ->once()
            ->with('foo', null);

        $this->model->shouldReceive('isInvalid')
            ->once()
            ->andReturn(true);

        $this->assertInvalidWithout($this->model, 'foo');
    }

    public function testAssertRespondsTo()
    {
        $this->assertRespondsTo($this->model, 'isValid');
    }

    public function testAssertBelongsTo()
    {
        $this->assertBelongsTo('EloquentStub', 'Foo');
    }

    public function testBelongsToMany()
    {
        $this->assertBelongsToMany('EloquentStub', 'Bars');
    }

    public function testHasOne()
    {
        $this->assertHasOne('EloquentStub', 'Baz');
    }

    public function testHasMany()
    {
        $this->assertHasMany('EloquentStub', 'Bats');
    }

}

class EloquentStub extends \Illuminate\Database\Eloquent\Model {

    public function isValid() {}
    public function isInvalid() {}

    public function foo()
    {
        return $this->belongsTo('Foo');
    }

    public function bars()
    {
        return $this->belongsToMany('Bars');
    }

    public function baz()
    {
        return $this->hasOne('Baz');
    }

    public function bats()
    {
        return $this->hasMany('Bat');
    }

}
