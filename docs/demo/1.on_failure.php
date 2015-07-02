<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\Di\Injector;
use Ray\Validation\Annotation\OnFailure;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\Valid;
use Ray\Validation\FailureInterface;
use Ray\Validation\ValidateModule;
use Ray\Validation\Validation;

class Fake
{
    private $defaultName = '';

    public function onGet()
    {
        echo sprintf('please input <form><input type="text" name="name" value="%s"></form>', (string) $this->defaultName) . PHP_EOL;
    }

    /**
     * @Valid
     */
    public function onPost($name)
    {
        echo "post {$name}" . PHP_EOL;
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
        if (!is_string($name)) {
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
        foreach ($failure->getMessages() as $name => $messages) {
            foreach ($messages as $message) {
                echo "Input '{$name}': {$message}" . PHP_EOL;
            }
        }
        list($this->defaultName) = $failure->getInvocation()->getArguments();
        $this->onGet();
    }
}

$loader = require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

/* @var $fake Fake */
$fake = (new Injector(new ValidateModule))->getInstance(Fake::class);

ob_start();
echo 'show form first:' . PHP_EOL;
$fake->onGet();
echo 'invalid post:' . PHP_EOL;
$fake->onPost(999);
echo 'valid post:' . PHP_EOL;
$fake->onPost('sunday');
$ob = ob_get_clean();

$expected = <<< EOT
show form first:
please input <form><input type="text" name="name" value=""></form>
invalid post:
Input 'name': name should be string.
please input <form><input type="text" name="name" value="999"></form>
valid post:
post sunday

EOT;

$works = $ob === $expected;
echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
