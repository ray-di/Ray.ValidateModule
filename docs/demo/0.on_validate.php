<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\Di\Injector;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\Valid;
use Ray\Validation\Exception\InvalidArgumentException;
use Ray\Validation\ValidateModule;
use Ray\Validation\Validation;

class Fake
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

$loader = require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$fake = (new Injector(new ValidateModule))->getInstance(Fake::class);
try {
    $fake->foo(0);
} catch (InvalidArgumentException $e) {
    $works = true;
}
echo(isset($works) ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
