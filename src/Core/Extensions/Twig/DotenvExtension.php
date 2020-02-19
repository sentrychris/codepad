<?php

namespace Versyx\Codepad\Core\Extensions\Twig;

use \Twig\TwigFunction;
use \Twig\Extension\AbstractExtension;

/**
 * Class DotenvExtension
 */
class DotenvExtension extends AbstractExtension
{
    /**
     * Define env accessor function
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