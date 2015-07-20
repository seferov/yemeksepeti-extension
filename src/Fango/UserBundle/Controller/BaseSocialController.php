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
     * @param array $networkData
     * @param User $user
     * @return Network
     */
    protected function createNetwork(array $networkData, User $user = null)
    {
        if (!$user) {
            $user = $this->getUser();
        }

        $network = new Network();
        $type = array_key_exists('type', $networkData) ? $networkData['type'] : $this->getType();
        $network->setType($type);
        $network->setUser($user);
        $network->setNetworkId($networkData['id']);
        $network->setRest(serialize($networkData));
        $network->setCreatedAt(new \DateTime('now'));
        $network->setDisplay($networkData['display']);

        if (array_key_exists('followers_count', $networkData)) {
            $network->setFollowersCount($networkData['followers_count']);
        }

        if (array_key_exists('token', $networkData)) {
            $network->setToken($networkData['token']);
        }
        if (array_key_exists('token_secret', $networkData)) {
            $network->setTokenSecret($networkData['token_secret']);
        }

        $this->getDoctrine()->getManager()->persist($network);

        return $network;
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