<?php
/**
 * This file is part of the Ray.ValidateModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\Validation;

use Aura\Filter\SubjectFilter;
use Aura\Filter\ValueFilter;
use Aura\Input\Builder;
use Aura\Input\BuilderInterface;
use Aura\Input\Filter;
use Aura\Input\FilterInterface;
use Ray\Di\AbstractModule;

class AuraInputModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new ValidateModule);
        $this->bind(BuilderInterface::class)->to(Builder::class);
        $this->bind(Filter::class)->toProvider(FilterProvider::class);
        $this->bind(FilterInterface::class)
        $this->bind(ValueFilter::class)->toProvider(ValueFilterProvider::class);
        $this->bind(SubjectFilter::class)->toProvider(SubjectFilterProvider::class);
    }
}
