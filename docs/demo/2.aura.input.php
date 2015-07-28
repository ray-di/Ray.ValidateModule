<?php

namespace Ray\Validation;

use Aura\Input\Filter;
use Aura\Input\Form;
use Ray\Di\Injector;
use Ray\Validation\Annotation\AuraInput;

require __DIR__ . '/autoload.php';

class Fake3Form extends AbstractAuraForm
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
     * {@inheritdoc}
     */
    public function __toString()
    {
        $form = $this->helper->form();
        $name = $this->get('name');
        $form .= $this->helper->form($name);
        $errorMessage = $this->getFilter()->getMessages('name');
        $form .= implode(',', $errorMessage);
        $form .= $this->helper->tag('/form');

        return $form;
    }
}

class Fake3
{
    use AuraInputTrait;

    public $view = [];

    public function onGet()
    {
        $this->view['form'] = $this->form;

        return $this;
    }

    /**
     * @AuraInput(onFailure="onFailure")
     */
    public function onPost($name)
    {
        return $name;
    }

    public function onFailure($name)
    {
        return $this->onGet();
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
$expected = '<form method="post" enctype="multipart/form-data"><form method="post" enctype="multipart/form-data" type="text" name="contact[name]" attribs="name   20 20" options="" value="1">First name must be alphabetic only.</form>';
echo((string) $result->view['form'] === $expected ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
