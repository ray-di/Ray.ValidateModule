<?php
/**
 * This file is part of the Ray.ValidateModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\Validation;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Ray\Validation\Annotation\Valid;

class ValidateModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(Reader::class)->to(AnnotationReader::class)->in(Scope::SINGLETON);
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Valid::class),
            [ValidateInterceptor::class]
        );
    }
}
