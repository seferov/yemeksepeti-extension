<?php

namespace Fango\UserBundle\Helper;

use Fango\UserBundle\Entity\User;

/**
 * Class UserHelper
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Helper
 */
class UserHelper
{
    /**
     * @param User $user
     * @return User
     */
    public static function fillDefaultValues(User $user)
    {
        $user->setCreatedAt(new \DateTime('now'));
        $user->setEnabled(true);
        $user->setEarnings(0);
        $user->setPaid(0);

        return $user;
    }
}