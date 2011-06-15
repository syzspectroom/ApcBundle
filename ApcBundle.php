<?php

namespace Kelu95\ApcBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApcBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
    
}
