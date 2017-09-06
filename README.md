# Ray.ValidateModule

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/badges/quality-score.png?b=1.x)](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/?branch=1.x)
[![Code Coverage](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/badges/coverage.png?b=1.x)](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/?branch=1.x)
[![Build Status](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/badges/build.png?b=1.x)](https://scrutinizer-ci.com/g/ray-di/Ray.ValidateModule/build-status/1.x)
[![Build Status](https://travis-ci.org/ray-di/Ray.ValidateModule.svg?branch=1.x)](https://travis-ci.org/ray-di/Ray.ValidateModule)

## Installation

### Composer install

    $ composer require ray/validate-module
 
### Module install

```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new ValidateModule);
    }
}
```
### Usage

Annotate target method with `@Valid` annotation.

```php
use Ray\Validation\Annotation\Valid;

class User
{
    /**
     * @Valid
     */
    public function createUser($name)
    {
        // ...
    }
```

Provide `onValidate` annotated method in same class for validation. Method can be named freely.

```php
use Ray\Validation\Annotation\OnValidate;
// ...

    /**
     * @OnValidate
     */
    public function onValidate($name)
    {
        $validation = new Validation;
        if (! is_string($name)) {
            $validation->addError('name', 'name should be string');
        }

        return $validation;
    }
```
Validate all parameters in validation method.  `addError($name, $message)` with invalid parameter name and message in the case of  validation failed.

`Ray\Validation\Exception\InvalidArgumentException` thrown on validation failed, But if `@OnFailure` annoted method exists, The result of `@OnFailure` method returns instead of original. 

```php
use Ray\Validation\Annotation\OnFailure;
// ...

    /**
     * @OnFailure
     */
    public function onFailure(FailureInterface $failure)
    {

        // original parameters
        list($this->defaultName) = $failure->getInvocation()->getArguments();

        // errors
        foreach ($failure->getMessages() as $name => $messages) {
            foreach ($messages as $message) {
                echo "Input '{$name}': {$message}" . PHP_EOL;
            }
        }
    }
```
If you need multiple validation in one class, You can name validation at annotation property as follows.

```php
use Ray\Validation\Annotation\Valid;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\OnInvalid;
// ...

    /**
     * @Valid("foo")
     */
    public function fooAction($name, $address, $zip)
    {
    
    /**
     * @OnValidate("foo")
     */
    public function onValidateFoo($name, $address, $zip)
    {

    // ...
    /**
     * @OnInvalid("foo")
     */
    public function onInvalidFoo(FailureInterface $failure)
    {
    // ...
```

### Demo

    $ php docs/demo/run.php
    // It works!

### Requirements

 * PHP 5.4+
 * hhvm

