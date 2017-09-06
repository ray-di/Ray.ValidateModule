<?php
/**
 * This file is part of the Ray.ValidateModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\Validation;

use Ray\Aop\MethodInvocation;

interface FailureInterface
{
    /**
     * Return validation error message
     *
     * @return string[]
     */
    public function getMessages();

    /**
     * Return method invocation
     *
     * @return MethodInvocation
     */
    public function getInvocation();
}
