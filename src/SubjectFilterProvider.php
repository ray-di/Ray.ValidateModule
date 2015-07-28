<?php
/**
 * This file is part of the Ray.ValidateModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\Validation;

use Aura\Filter\FilterFactory;
use Ray\Di\ProviderInterface;

class SubjectFilterProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return (new FilterFactory())->newSubjectFilter();
    }
}
