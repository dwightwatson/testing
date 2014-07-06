<?php

class ModelHelpersTest extends \PHPUnit_Framework_TestCase {

    use \Watson\Testing\ModelHelpers;

    public $model;

    public function setUp()
    {
        $this->model = Mockery::mock('EloquentStub');
    }

    public function testGetDefaultRules()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->once()
            ->andReturn('foo');

        $this->assertEquals('foo', $this->getDefaultRules($this->model));
    }

    public function testGetAttributeRules()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->once()
            ->andReturn(['zero' => 'bar']);

        $result = $this->getAttributeRules($this->model, 'zero');

        $this->assertEquals(['bar'], $result);
    }

    public function testAssertValidatesWithNoParameter()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->once()
            ->andReturn(['foo' => 'required']);

        $this->assertValidatesRequired($this->model, 'foo');
    }

    public function testAssertValidatesWithOneParameter()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->once()
            ->andReturn(['foo' => 'min:5']);

        $this->assertValidatesMin($this->model, 'foo', 5);
    }

    public function testAssertValidatesWithTwoParameters()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->once()
            ->andReturn(['foo' => 'required_if:foo,bar']);

        $this->assertValidatesRequiredIf($this->model, 'foo', 'foo', 'bar');
    }

    public function testAssertValidatesWithMultipleParameters()
    {
        $this->model->shouldReceive('getDefaultRules')
            ->twice()
            ->andReturn(['foo' => 'not_in:foo,bar,baz']);

        // Test array and string syntax.
        $this->assertValidatesNotIn($this->model, 'foo', ['foo', 'bar', 'baz']);
        $this->assertValidatesNotIn($this->model, 'foo', 'foo,bar,baz');
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

    public function getDefaultRules() {}
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
