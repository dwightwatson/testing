<?php namespace Watson\Testing;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use \Mockery;

trait ModelHelpers {

    /**
     * Assert that the provided model is valid. Requires that the model has a
     * method called isValid().
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $message
     * @return void
     */
    public function assertValid(Model $model, $message = null)
    {
        $message = $message ?: 'Expected ' . get_class($model) . ' model to be valid.';

        $this->assertRespondsTo($model, 'isValid', 'Expected isValid() method on model.');
        $this->assertTrue($model->isValid(), $message);
    }

    /**
     * Assert that the provided model is invalid. Requires that the model has
     * a method called isInvalid().
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $message
     * @return void
     */
    public function assertInvalid(Model $model, $message = null)
    {
        $message = $message ?: 'Expected ' . get_class($model) . ' model to be invalid.';

        $this->assertRespondsTo($model, 'isInvalid', 'Expected isInvalid() method on model.');
        $this->assertTrue($model->isInvalid(), $message);
    }

    /**
     * Assert that the provided model is valid with the provided attribute and
     * value.
     *
     * @param  Model $model
     * @param  string $attribute
     * @param  mixed $value
     * @param  string $message
     * @return void
     */
    public function assertValidWith(Model $model, $attribute, $value = null, $message = null)
    {
        $message = $message ?: 'Expected ' . get_class($model) . ' to be valid with ' . $attribute . ' as ' . $value . '.';

        $model->$attribute = $value;

        $this->assertValid($model, $message);
    }

    /**
     * Assert that the provided model is valid without the provided attribute.
     *
     * @param  Model $model
     * @param  string $attribute
     * @param  string $message
     * @return void
     */
    public function assertValidWithout(Model $model, $attribute, $message = null)
    {
        $message = $message ?: ' Expected ' . get_class($model) . ' to be valid without ' . $attribute . '.';

        $this->assertValidWith($model, $attribute, null, $message);
    }

    /**
     * Assert that the provided model is invalid with the provided attribute
     * and value.
     *
     * @param  Model $model
     * @param  string $attribute
     * @param  mixed $value
     * @param  string $message
     * @return void
     */
    public function assertInvalidWith(Model $model, $attribute, $value = null, $message = null)
    {
        $message = $message ?: 'Expected ' . get_class($model) . ' to be invalid with ' . $attribute . ' as ' . $value . '.';

        $model->$attribute = $value;

        $this->assertInvalid($model, $message);   
    }

    /**
     * Assert that the provided model is invalid without the provided
     * attribute.
     *
     * @param  Model $model
     * @param  string $attribute
     * @param  string $message
     * @return void
     */
    public function assertInvalidWithout(Model $model, $attribute, $message = null)
    {
        $message = $message ?: 'Expected ' . get_class($model) . ' to be includ without ' . $attribute . '.';

        $this->assertInvalidWith($model, $attribute, null, $message);
    }

    /**
     * Assert that the provided object has the provided method.
     *
     * @param  string $class
     * @param  mixed $method
     * @param  string $message
     * @return void
     */
    public function assertRespondsTo($class, $method, $message = 'Expected class to have method.')
    {
        $this->assertTrue(method_exists($class, $method), $message);
    }

    /**
     * Assert that the provided class belongs to the provided relation.
     *
     * @param  string $class
     * @param  string $relation
     * @return void
     */
    public function assertBelongsTo($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'belongsTo');
    }

    /**
     * Assert that the provided class belongs to many of the provided relation.
     *
     * @param  string $class
     * @param  string $relation
     * @return void
     */
    public function assertBelongsToMany($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'belongsToMany');
    }

    /**
     * Assert that the provided class has one of the provided relation.
     *
     * @param  string $class
     * @param  string $relation
     * @return void
     */
    public function assertHasOne($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'hasOne');
    }

    /**
     * Assert that the provided class has one of the provided relation.
     *
     * @param  string $class
     * @param  string $relation
     * @return void
     */
    public function assertHasMany($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'hasMany');
    }

    /** 
     * Assert that the provided relationship of type exists on the provided
     * class.
     *
     * @param  string $class
     * @param  string $relationship
     * @param  string $type
     * @return void
     */
    public function assertRelationship($class, $relationship, $type)
    {
        $this->assertRespondsTo($class, $relationship);

        $args = $this->getRelationshipArguments($class, $relationship, $type);

        $class = Mockery::mock($class."[$type]")
            ->shouldIgnoreMissing()
            ->asUndefined();

        switch(count($args))
        {
            case 1:
                $class->shouldReceive($type)
                      ->once()
                      ->with('/' . str_singular($relationship) . '/i')
                      ->andReturn(Mockery::self());
                break;

            case 2:
                $class->shouldReceive($type)
                      ->once()
                      ->with('/' . str_singular($relationship) . '/i', $args[1])
                      ->andReturn(Mockery::self());
                break;

            case 3:
                $class->shouldReceive($type)
                      ->once()
                      ->with('/' . str_singular($relationship) . '/i', $args[1], $args[2])
                      ->andReturn(Mockery::self());
                break;

            case 4:
                $class->shouldReceive($type)
                      ->once()
                      ->with('/' . str_singular($relationship) . '/i', $args[1], $args[2], $args[3])
                      ->andReturn(Mockery::self());
                break;

            default:
                $class->shouldReceive($type)
                      ->once()
                      ->andReturn(Mockery::self());
                break;
        }

        $class->$relationship();
    }

    /**
     * Get the arguments of the relationship.
     *
     * @param  string $class
     * @param  string $relationship
     * @param  string $type
     * @return array
     */
    public function getRelationshipArguments($class, $relationship, $type) {
        $mocked = Mockery::mock($class."[$type]")
            ->shouldIgnoreMissing()
            ->asUndefined();

        $args = [];

        $mocked->shouldReceive($type)
            ->once()
            ->andReturnUsing(function() use (&$args)
            {
                $args = func_get_args();
                return Mockery::self();
            });

        $mocked->$relationship();

        return $args;
    }

}
