<?php
/**
 * This file is part of the Ray.ValidateModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\Validation\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class OnFailure
{
    /**
     * Validation name
     *
     * @var string
     */
    public $value = '';
}
