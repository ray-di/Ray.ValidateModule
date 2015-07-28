<?php
/**
 * This file is part of the _package_ package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\Validation;

use Aura\Html\HelperLocator;
use Aura\Html\HelperLocatorFactory;
use Aura\Input\Form;
use Ray\Di\Di\Inject;

abstract class AbstractAuraForm extends Form
{
    /**
     * @var HelperLocator
     */
    protected $helper;

    /**
     * @Inject
     */
    public function setFormHelper(HelperLocatorFactory $factory)
    {
        $this->helper = $factory->newInstance();
    }

    /**
     * @return string
     *
     * @throws \Aura\Input\Exception\NoSuchInput
     */
    abstract public function __toString();
}
