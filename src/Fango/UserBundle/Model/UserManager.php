<?php

namespace Fango\UserBundle\Model;

use Fango\UserBundle\Entity\User;
use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

/**
 * Class UserManager
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Model
 */
class UserManager extends BaseUserManager
{
    /**
     * @return User
     */
    public function createUser()
    {
        $user = new User();

        $user->setCreatedAt(new \DateTime('now'));
        $user->setEarnings(0);
        $user->setPaid(0);

        return $user;
    }
}