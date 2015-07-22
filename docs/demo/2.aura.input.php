<?php

namespace Ray\Validation;

use Aura\Html\HelperLocator;
use Aura\Html\HelperLocatorFactory;
use Aura\Input\Filter;
use Aura\Input\Form;
use Ray\Di\Injector;
use Ray\Validation\Annotation\OnFailure;
use Ray\Validation\Annotation\OnValidate;
use Ray\Validation\Annotation\Valid;

require __DIR__ . '/autoload.php';

class ContactForm extends Form
{
    public function init()
    {
        $this->setName('contact');
        $this->setField('name', 'text')
            ->setAttribs([
                'id' => 'name',
                'size' => 20,
                'maxlength' => 20,
            ]);
        /** @var $filter Filter */
        $filter = $this->getFilter();
        $filter->setRule(
            'name',
            'First name must be alphabetic only.',
            function ($value) {
                return ctype_alpha($value);
            }
        );
    }

    /**
     * @param FailureInterface $failure
     *
     * @OnFailure
     */
    public function __toString()
    {
        /** @var $formHelper \Aura\Html\Helper\Form */
        $helper = (new HelperLocatorFactory)->newInstance();
        $form = $helper->form();
        $form .= $helper->form($this->form->get('name'));
        $message = $this->form->getFilter()->getMessages('name');
        $form .= implode(',', $message);
        $form .= $helper->tag('/form');

        return $form;
    }
}

class Fake3
{
    /**
     * @var Filter
     */
    private $filer;

    /**
     * @var ContactForm
     */
    private $form;


    public function __construct(ContactForm $form)
    {
        $this->form = $form;
    }

    public function onGet()
    {
        $this['form'] = (string) $this->form;
    }

    /**
     * @Valid
     */
    public function onPost($name)
    {
        return $name;
    }

    /**
     * @param $name
     *
     * @return Validation
     * @OnValidate
     */
    public function onValidateOnPost($name)
    {
        $this->form->fill(compact('name'));
        $isValid = $this->form->filter();
        $validation = new Validation;
        if ($isValid) {
            return $validation;
        }
        $messages = $this->form->getFilter()->getMessages();
        $validation->addErrors($messages);

        return $validation;
    }

    public function onInvalidOnPost(FailureInterface $failure)
    {
        $this['form'] = (string) $this->form;
    }

}

/* @var $fake Fake3 */
try {
    $fake = (new Injector(new AuraInputModule))->getInstance(Fake3::class);
} catch (\Exception $e) {
    echo $e;
    exit;
}
$result = $fake->onPost(1);
$expected = [
    [
        'name' =>
            [
                'First name must be alphabetic only.',
            ],
    ],
    [
        1
    ],
];
echo($result === $expected ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
