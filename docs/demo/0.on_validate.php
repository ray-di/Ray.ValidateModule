<?php
/**
 * This file is part of the Ray.ValidateModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
use Ray\Di\Injector;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\Valid;
use Ray\Validation\Exception\InvalidArgumentException;
use Ray\Validation\ValidateModule;
use Ray\Validation\Validation;

require __DIR__ . '/autoload.php';

class Fake0
{
    /**
     * @Valid
     */
    public function foo($name)
    {
    }

    /**
     * @param $name
     *
     * @return Validation
     * @OnValidate()
     */
    public function onValidateFoo($name)
    {
        $validation = new Validation;
        if (! is_string($name)) {
            $validation->addError('name', 'name should be string.');
        }

        return $validation;
    }
}

$fake = (new Injector(new ValidateModule))->getInstance(Fake0::class);
try {
    $fake->foo(0);
} catch (InvalidArgumentException $e) {
    $works = true;
}
echo(isset($works) ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
