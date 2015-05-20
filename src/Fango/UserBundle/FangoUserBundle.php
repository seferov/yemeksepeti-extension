<?php

namespace Fango\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FangoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
