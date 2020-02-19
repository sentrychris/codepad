<?php

namespace Versyx\Codepad\Core\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class DotenvExtension.
 */
class DotenvExtension extends AbstractExtension
{
    /**
     * Define env accessor function.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('env', 'env'),
        ];
    }
}
