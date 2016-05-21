<?php


namespace Kusebauch\NetteSecurityAnnotation\Tests;

use Latte;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class LatteFactory implements ILatteFactory
{

    /**
     * @return Latte\Engine
     */
    function create()
    {
        return new Latte\Engine();
    }
}