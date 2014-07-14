Testing, PHPUnit helpers for Laravel
====================================

[![Build Status](https://travis-ci.org/dwightwatson/testing.svg?branch=master)](https://travis-ci.org/dwightwatson/testing)

Testing contains traits with helpers for testing models and controllers in Laravel. It helps you assert the validity of your models (assuming the use of [watson/validating](https://github.com/dwightwatson/validating)) as well as the relationships of your models. It also allows you to test the responses of your controllers.

# Installation

Simply add the package to your `composer.json` file and run `composer update`.

```
"watson/testing": "1.0.*"
```

## Overview

To use the test helpers in your tests, simply bring the trait in to your test file.

```php
class UsersControllerTest extends TestCase {
	use Watson\Testing\ControllerHelpers;
}
```

```php
class UserTest extends TestCase {
	use Watson\Testing\ModelHelpers;
}
```

If you'd prefer to use the test helpers globally, just use this trait in your `TestCase.php`.

```php
class TestCase extends Illuminate\Foundation\Testing\TestCase {
	use Watson\Testing\TestingTrait;
}
```

## Controller testing

### assertViewIs($expectedView, $message = null);

```php
// Controller
return View::make('users.index');

// Test
$this->assertViewIs('users.index');
```

Ensure that the view used in the response is the one you expected.

## Model testing

### Model validations

If you're using [watson/validating](https://github.com/dwightwatson/validating) on your models it is really easy to test your validations. We will use the following `User` model for these examples.

```php
$user = new User;
```

#### assertValid(Model $model, $message = null)

```php
$user->email = 'example@example.com';

$this->assertValid($user);
```

#### assertInvalid(Model $model, $message = null)

```php
$user->email = 'foo';

$this->assertInvalid($user);
```

If you want to easily check if a model is valid or invalid with or without a certain attribute, there a number of helpers for quickly asserting that this is the case.

#### assertValidWith(Model $model, $attribute, $value = null, $message = null)

```php
$this->assertValidWith($user, 'email', 'example@example.com');
```

#### assertValidWithout(Model $model, $attribute, $message = null)

```php
$this->assertValidWithout($user, 'last_name')
```

#### assertInvalidWith(Model $model, $attribute, $value = null, $message = null)

```php
$this->assertInvalidWith($user, 'email', 'foo');
```

#### assertInvalidWithout(Model $model, $attribute, $message = null)

```php
$this->assertInvalidWithout($user, 'email');
```

### Specific model validations

If you'd prefer an easier (and more readable) way of asserting the validations on your model you might like to try specific model validations. They work with [watson/validating](https://github.com/dwightwatson/validating) or any other validation trait that complies with `Watson\Validating\ValidatingInterface` (that is, has a `getDefaultRules` method).

#### assertValidatesWith(Model $mode, $attribute, $rule, $message = null)

```php
// Assert that the email attribute is required.
$this->assertValidatesWith($user, 'email', 'required');
$this->assertValidatesRequired($user, 'email');
```

Here is the list of included Laravel default validation assertions:

* assertValidatesAccepted(Model $model, $attribute, $message = null)
* assertValidatesActiveUrl(Model $model, $attribute)
* assertValidatesAfter(Model $model, $attribute, $date)
* assertValidatesAlpha(Model $model, $attribute)
* assertValidatesAlphaDash(Model $model, $attribute)
* assertValidatesAlphaNum(Model $model, $attribute)
* assertValidatesArray(Model $model, $attribute)
* assertValidatesBefore(Model $model, $attribute, $date)
* assertValidatesBetween(Model $model, $attribute, $min, $max)
* assertValidatesConfirmed(Model $model, $attribute)
* assertValidatesDate(Model $model, $attribute)
* assertValidatesDate(Model $model, $attribute)
* assertValidatesDifferent(Model $model, $attribute, $field)
* assertValidatesDigits(Model $model, $attribute, $value)
* assertValidatesDigitsBetween(Model $model, $attribute, $min, $max)
* assertValidatesBoolean(Model $model, $attribute)
* assertValidatesEmail(Model $model, $attribute)
* assertValidatesExists(Model $model, $attribute, $parameters)
* assertValidatesImage(Model $model, $attribute)
* assertValidatesIn(Model $model, $attribute, $values)
* assertValidatesInteger(Model $model, $attribute)
* assertValidatesIp(Model $model, $attribute)
* assertValidatesMax(Model $model, $attribute, $value)
* assertValidatesMimes(Model $model, $attribute, $values)
* assertValidatesMin(Model $model, $attribute, $value)
* assertValidatesNotIn(Model $model, $attribute, $values)
* assertValidatesNumeric(Model $model, $attribute)
* assertValidatesRegex(Model $model, $attribute, $pattern)
* assertValidatesRequired(Model $model, $attribute)
* assertValidatesRequiredIf(Model $model, $attribute, $field, $value)
* assertValidatesRequiredWith(Model $model, $attribute, $values)
* assertValidatesRequiredWithAll(Model $model, $attribute, $values)
* assertValidatesRequiredWithout(Model $model, $attribute, $values)
* assertValidatesRequiredWithoutAll(Model $model, $attribute, $values)
* assertValidatesSame(Model $model, $attribute, $field)
* assertValidatesSize(Model $model, $attribute, $value)
* assertValidatesTimezone(Model $model, $attribute)
* assertValidatesUnique(Model $model, $attribute, $parameters)


### Model relationships

You can assert the different relationships exist on your model.

#### assertBelongsTo($class, $relation)

Ensure that a post belongs to a user.

```php
$this->assertBelongsTo($post, 'user');
```

#### assertBelongsToMany($class, $relation)

Ensure that a tag belongs to many posts.

```php
$this->assertBelongsToMany($tag, 'posts');
```

#### assertHasOne($class, $relation)

Ensure that a user has one profile.

```php
$this->assertHasOne($user, 'profile');
```

#### assertHasMany($class, $relation)

Ensure that a user has many posts.

```php
$this->assertHasMany($user, 'posts');
```

## Credits

This package builds upon the work of the now unmaintained [way/laravel-test-helpers](https://github.com/JeffreyWay/Laravel-Test-Helpers) and includes code from the unmerged pull requests of [SammyK](https://github.com/JeffreyWay/Laravel-Test-Helpers/pull/52/files), [effi](https://github.com/JeffreyWay/Laravel-Test-Helpers/pull/41), [mrevd](https://github.com/JeffreyWay/Laravel-Test-Helpers/pull/42) and [sorora](https://github.com/JeffreyWay/Laravel-Test-Helpers/pull/8/files).

I decided to continue the development of these helpers because I prefer testing with the tool that ships with the framework, and I really like PHPUnit.