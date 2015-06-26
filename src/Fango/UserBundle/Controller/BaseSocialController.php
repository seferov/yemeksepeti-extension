<?php

namespace Fango\UserBundle\Controller;

use Fango\UserBundle\Entity\Network;
use Fango\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class BaseSocialController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
abstract class BaseSocialController extends Controller
{
    abstract function getType();

    /**
     * @param array $userData
     * @param User $user
     */
    protected function createNetwork(array $userData, User $user)
    {
        $network = new Network();
        $network->setType($this->getType());
        $network->setUser($user);
        $network->setNetworkId($userData['id']);
        $network->setRest(serialize($userData));
        $network->setCreatedAt(new \DateTime('now'));
        $network->setDisplay($userData['display']);

        if (array_key_exists('token', $userData)) {
            $network->setToken($userData['token']);
        }
        if (array_key_exists('token_secret', $userData)) {
            $network->setTokenSecret($userData['token_secret']);
        }

        $this->getDoctrine()->getManager()->persist($network);
    }

    /**
     * @param User $user
     * @param array $userData
     */
    protected function authenticateUser(User $user, array $userData = [])
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        if (array_key_exists('token', $userData) || array_key_exists('token_secret', $userData)) {
            foreach ($user->getNetworks() as $network) {
                if ($network->getType() == $this->getType()) {
                    if (array_key_exists('token', $userData)) {
                        $network->setToken($userData['token']);
                    }
                    if (array_key_exists('token_secret', $userData)) {
                        $network->setTokenSecret($userData['token_secret']);
                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($network);
                    $em->flush();
                    break;
                }
            }
        }
    }
}