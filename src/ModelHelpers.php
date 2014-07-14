<?php namespace Watson\Testing;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;
use \Mockery;

trait ModelHelpers {

    /**
     * Assert that the provided model is valid. Requires that the model has a
     * method called isValid().
     *
     * @param  Model   $model
     * @param  string  $message
     * @return void
     */
    public function assertValid(Model $model, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} model to be valid.";

        $this->assertRespondsTo($model, 'isValid', 'Expected isValid() method on model.');
        $this->assertTrue($model->isValid(), $message);
    }

    /**
     * Assert that the provided model is invalid. Requires that the model has
     * a method called isInvalid().
     *
     * @param  Model   $model
     * @param  string  $message
     * @return void
     */
    public function assertInvalid(Model $model, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} model to be invalid.";

        $this->assertRespondsTo($model, 'isInvalid', 'Expected isInvalid() method on model.');
        $this->assertTrue($model->isInvalid(), $message);
    }

    /**
     * Assert that the provided model is valid with the provided attribute and
     * value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  string  $message
     * @return void
     */
    public function assertValidWith(Model $model, $attribute, $value = null, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} to be valid with $attribute as $value.";

        $model->setAttribute($attribute, $value);

        $this->assertValid($model, $message);
    }

    /**
     * Assert that the provided model is valid without the provided attribute.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $message
     * @return void
     */
    public function assertValidWithout(Model $model, $attribute, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} to be valid without $attribute.";

        $this->assertValidWith($model, $attribute, null, $message);
    }

    /**
     * Assert that the provided model is invalid with the provided attribute
     * and value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  string  $message
     * @return void
     */
    public function assertInvalidWith(Model $model, $attribute, $value = null, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} to be invalid with $attribute as $value.";

        $model->setAttribute($attribute, $value);

        $this->assertInvalid($model, $message);   
    }

    /**
     * Assert that the provided model is invalid without the provided
     * attribute.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $message
     * @return void
     */
    public function assertInvalidWithout(Model $model, $attribute, $message = null)
    {
        $message = $message ?: "Expected {get_class($model)} to be included without $attribute.";

        $this->assertInvalidWith($model, $attribute, null, $message);
    }

    /**
     * Get the default rules of a model.
     *
     * @param  Model  $model
     * @return array
     */
    public function getDefaultRules(Model $model)
    {
        $this->assertRespondsTo($model, 'getDefaultRules');

        return $model->getDefaultRules();
    }

    /**
     * Get the rules of a given attribute as an array.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return array
     */
    public function getAttributeRules(Model $model, $attribute)
    {
        $rules = $this->getDefaultRules($model);

        if (array_key_exists($attribute, $rules))
        {
            return is_array($rules[$attribute]) ? $rules[$attribute] : explode('|', $rules[$attribute]);
        }
    }

    /**
     * Assert that the model validates a given attribute with a given rule.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $rule
     * @param  string  $message
     * @return void
     */
    public function assertValidatesWith(Model $model, $attribute, $rule, $message = null)
    {
        $rules = $this->getAttributeRules($model, $attribute);

        $this->assertContains($rule, $rules, $message);
    }

    /**
     * Assert that the model validates a given attribute with 'accepted'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesAccepted(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'accepted', "Expected $attribute to have 'accepted' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'active_url'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesActiveUrl(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'active_url', "Expected $attribute to have 'active_url' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'date' and a date.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $date
     * @return void
     */
    public function assertValidatesAfter(Model $model, $attribute, $date)
    {
        $this->assertValidatesWith($model, $attribute, "after:$date", "Expected $attribute to have 'after' validation with date $date.");
    }

    /**
     * Assert that the model validates a given attribute with 'alpha'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesAlpha(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'alpha', "Expected $attribute to have 'alpha' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'alpha_dash'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesAlphaDash(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'alpha_dash', "Expected $attribute to have 'alpha_dash' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'alpha_num'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesAlphaNum(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'alpha_num', "Expected $attribute to have 'alpha_num' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'array'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesArray(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'array', "Expected $attribute to have 'array' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'before' and a 
     * date.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $date
     * @return void
     */
    public function assertValidatesBefore(Model $model, $attribute, $date)
    {
        $this->assertValidatesWith($model, $attribute, "date:$date", "Expected $attribute to have 'date' validation with $date.");
    }

    /**
     * Assert that the model validates a given attribute with 'between' and two 
     * dates.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $min
     * @param  int     $max
     * @return void
     */
    public function assertValidatesBetween(Model $model, $attribute, $min, $max)
    {
        $this->assertValidatesWith($model, $attribute, "between:$min,$max", "Expected $attribute to have 'between' validation with $min,$max.");
    }

    /**
     * Assert that the model validates a given attribute with 'confirmed'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesConfirmed(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'contirmed', "Expected $attribute to have 'confirmed' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'date'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesDate(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'date', "Expected $attribute to have 'date' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'format' and a
     * format.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $format
     * @return void
     */
    public function assertValidatesDateFormat(Model $model, $attribute, $format)
    {
        $this->assertValidatesWith($model, $attribute, "format:$format", "Expected $attribute to have 'format' validation with $format.");
    }

    /**
     * Assert that the model validates a given attribute with 'different'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $field
     * @return void
     */
    public function assertValidatesDifferent(Model $model, $attribute, $field)
    {
        $this->assertValidatesWith($model, $attribute, "different:$field", "Expected $attribute to have 'different' validation with $field.");
    }

    /**
     * Assert that the model validates a given attribute with 'digits' and a
     * value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $value
     * @return void
     */
    public function assertValidatesDigits(Model $model, $attribute, $value)
    {
        $this->assertValidatesWith($model, $attribute, "digits:$value", "Expected $attribute to have 'digits' validation with $value.");
    }

    /**
     * Assert that the model validates a given attribute with 'digits_between'
     * and two values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $min
     * @param  int     $max
     * @return void
     */
    public function assertValidatesDigitsBetween(Model $model, $attribute, $min, $max)
    {
        $this->assertValidatesWith($model, $attribute, "digits_between:$min,$max", "Expected $attribute to have 'digits_between' validation with $min,$max.");
    }

    /**
     * Assert that the model validates a given attribute with 'boolean'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesBoolean(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'boolean', "Expected $attribute to have 'booleam' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'email'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesEmail(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'email', "Expected $attribute to have 'email' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'exists' and
     * parameters.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $parameters
     * @return void
     */
    public function assertValidatesExists(Model $model, $attribute, $parameters)
    {
        $this->assertValidatesWith($model, $attribute, "exists:$parameters", "Expected $attribute to have 'exists' validation with $parameters");
    }

    /**
     * Assert that the model validates a given attribute with 'image'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesImage(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'image', "Expected $attribute to have 'image' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'in' and values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesIn(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "in:$values", "Expected $attribute to have 'in' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'integer'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesInteger(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'integer', "Expected $attribute to have 'integer' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'ip'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesIp(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'ip', "Expected $attribute to have 'ip' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'max' and a value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $Value
     * @return void
     */
    public function assertValidatesMax(Model $model, $attribute, $value)
    {
        $this->assertValidatesWith($model, $attribute, "max:$value", "Expected $attribute to have 'max' validation with $value.");
    }

    /**
     * Assert that the model validates a given attribute with 'mines' and 
     * values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesMimes(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "mimes:$values", "Expected $attribute to have 'mimes' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'min' and a value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $value
     * @return void
     */
    public function assertValidatesMin(Model $model, $attribute, $value)
    {
        $this->assertValidatesWith($model, $attribute, "min:$value", "Expected $attribute to have 'min' validation with $value.");
    }

    /**
     * Assert that the model validates a given attribute with 'not_in' and
     * values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesNotIn(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "not_in:$values", "Expected $attribute to have 'not_in' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'numeric'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesNumeric(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'numeric', "Expected $attribute to have 'numeric' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'regex' and a
     * pattern.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $pattern
     * @return void
     */
    public function assertValidatesRegex(Model $model, $attribute, $pattern)
    {
        $this->assertValidatesWith($model, $attribute, "regex:$pattern", "Expected $attribute to have 'regex' validation with $pattern.");
    }

    /**
     * Assert that the model validates a given attribute with 'required'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesRequired(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'required', "Expected $attribute to have 'required' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'required_if' and
     * a field and value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $field
     * @param  string  $value
     * @return void
     */
    public function assertValidatesRequiredIf(Model $model, $attribute, $field, $value)
    {
        $this->assertValidatesWith($model, $attribute, "required_if:$field,$value", "Expected $attribute to have 'required_if' validation with $field,$value.");
    }

    /**
     * Assert that the model validates a given attribute with 'required_with'
     * and values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesRequiredWith(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "required_with:$values", "Expected $attribute to have 'required_with' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'with_all' and
     * values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesRequiredWithAll(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "required_with_all:$values", "Expected $attribute to have 'required_with_all' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'required_without'
     * and values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesRequiredWithout(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "required_without:$values", "Expected $attribute to have 'required_without' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 
     * 'required_without_all' and values.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  mixed   $values
     * @return void
     */
    public function assertValidatesRequiredWithoutAll(Model $model, $attribute, $values)
    {
        $values = implode(',', (array) $values);

        $this->assertValidatesWith($model, $attribute, "required_without_all:$values", "Expected $attribute to have 'required_without_all' validation with $values.");
    }

    /**
     * Assert that the model validates a given attribute with 'same' and a
     * field.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $field
     * @return void
     */
    public function assertValidatesSame(Model $model, $attribute, $field)
    {
        $this->assertValidatesWith($model, $attribute, "same:$field", "Expected $attribute to have 'same' validation with $field.");
    }

    /**
     * Assert that the model validates a given attribute with 'size' and a
     * value.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  int     $value
     * @return void
     */
    public function assertValidatesSize(Model $model, $attribute, $value)
    {
        $this->assertValidatesWith($model, $attribute, "size:$value", "Expected $attribute to have 'size' validation with $value.");
    }

    /**
     * Assert that the model validates a given attribute with 'timezone'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesTimezone(Model $model, $attribute)
    {
        $this->assertValidatesWith($model, $attribute, 'timezone', "Expected $attribute to have 'timezone' validation.");
    }

    /**
     * Assert that the model validates a given attribute with 'unique' and a
     * table, column, ignored, ID column and additional parameters.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @param  string  $parameters
     * @return void
     */
    public function assertValidatesUnique(Model $model, $attribute, $parameters)
    {
        $this->assertValidatesWith($model, $attribute, "unique:$parameters", "Expected $attribute to have 'unique' validation with $parameters.");
    }

    /**
     * Assert that the model validates a given attribute with 'url'.
     *
     * @param  Model   $model
     * @param  string  $attribute
     * @return void
     */
    public function assertValidatesUrl(Model $model, $attribute)
    {
        $this->assertValidateWith($model, $attribute, 'url', "Expected $attribute to have 'url' validation.");
    }

    /**
     * Assert that the provided object has the provided method.
     *
     * @param  mixed   $class
     * @param  mixed   $method
     * @param  string  $message
     * @return void
     */
    public function assertRespondsTo($class, $method, $message = 'Expected class to have method.')
    {
        $this->assertTrue(method_exists($class, $method), $message);
    }

    /**
     * Assert that the provided class belongs to the provided relation.
     *
     * @param  mixed   $class
     * @param  string  $relation
     * @return void
     */
    public function assertBelongsTo($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'belongsTo');
    }

    /**
     * Assert that the provided class belongs to many of the provided relation.
     *
     * @param  mixed   $class
     * @param  string  $relation
     * @return void
     */
    public function assertBelongsToMany($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'belongsToMany');
    }

    /**
     * Assert that the provided class has one of the provided relation.
     *
     * @param  mixed   $class
     * @param  string  $relation
     * @return void
     */
    public function assertHasOne($class, $relation)
    {
        $this->assertRelationship($class, $relation, 'hasOne');
    }

    /**
     * Assert that the provided class has one of the provided relation.
     *
     * @param  mixed   $class
     * @param  string  $relation
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
     * @param  mixed   $class
     * @param  string  $relationship
     * @param  string  $type
     * @return void
     */
    public function assertRelationship($class, $relationship, $type)
    {
        $class = is_string($class) ? $class : get_class($class);

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
     * @param  string  $class
     * @param  string  $relationship
     * @param  string  $type
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
