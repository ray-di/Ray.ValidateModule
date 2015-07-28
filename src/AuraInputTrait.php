<?php
/**
 * This file is part of the _package_ package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\Validation;

use Aura\Input\Form;
use Ray\Di\Di\Inject;

trait AuraInputTrait
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @param Fake3Form $form
     *
     * @Inject()
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param array $submit
     *
     * @return bool
     */
    public function isValidForm(array $submit)
    {
        $this->form->fill($submit);
        $isValid = $this->form->filter();

        return $isValid;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }
}
