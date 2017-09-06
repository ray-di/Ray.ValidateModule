<?php
/**
 * This file is part of the Ray.ValidateModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
use Ray\Di\Injector;
use Ray\Validation\Annotation\OnFailure;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\Valid;
use Ray\Validation\FailureInterface;
use Ray\Validation\ValidateModule;
use Ray\Validation\Validation;

require __DIR__ . '/autoload.php';

class Fake1
{
    public function onGet()
    {
    }

    /**
     * @Valid
     */
    public function onPost($name)
    {
    }

    /**
     * @param $name
     *
     * @return Validation
     * @OnValidate
     */
    public function onValidateOnPost($name)
    {
        $validation = new Validation;
        if (! is_string($name)) {
            $validation->addError('name', 'name should be string.');
        }

        return $validation;
    }

    /**
     * @param FailureInterface $failure
     *
     * @OnFailure
     */
    public function onInvalidOnPost(FailureInterface $failure)
    {
        $msg = $failure->getMessages();
        $args = (array) $failure->getInvocation()->getArguments();

        return [$msg, $args];
    }
}

/* @var $fake Fake */
$fake = (new Injector(new ValidateModule))->getInstance(Fake1::class);
$expected = [
    ['name' => ['name should be string.']],
    [100]
];

$result = $fake->onPost(100);
echo($result === $expected ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
