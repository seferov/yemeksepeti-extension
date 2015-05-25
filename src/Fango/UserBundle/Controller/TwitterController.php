<?php

namespace Fango\UserBundle\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Fango\UserBundle\Entity\Network;
use Fango\UserBundle\Entity\User;
use Fango\UserBundle\Helper\UserHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class TwitterController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
class TwitterController extends Controller
{
    public function loginAction()
    {
        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'));
        $requestToken = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->generateUrl('fango_user_twitter_check', [], true)));
        $url = $connection->url('oauth/authenticate', array('oauth_token' => $requestToken['oauth_token']));

        return $this->redirect($url);
    }

    public function checkAction(Request $request)
    {
        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'));
        $content = $connection->oauth('oauth/access_token', [
            'oauth_verifier' => $request->get('oauth_verifier'),
            'oauth_token' => $request->get('oauth_token')
        ]);

        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'), $content['oauth_token'], $content['oauth_token_secret']);
        $userData = $connection->get("account/verify_credentials");

        $userData = json_decode(json_encode($userData), true);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FangoUserBundle:User')->getUserBySocialId($userData['id'], 'twitter');

        if (!$user) {
            if ($this->getUser()) {
                $user = $this->getUser();
                $this->createNetwork($userData, $user);
            }
            else {
                $user = $this->createUser($userData);
                $em->persist($user);
            }

            $em->flush();
        }

        $this->authenticateUser($user);

        return $this->redirectToRoute('fango_main_dashboard');
    }

    /**
     * @param array $userData
     * @return User
     */
    private function createUser(array $userData)
    {
        $user = new User();
        $user->setUsername($userData['screen_name']);
        $user->setEmail('none');
        $user->setFullname($userData['name']);

        $this->createNetwork($userData, $user);

        $user = UserHelper::fillDefaultValues($user);

        $user->setPlainPassword('randomPass');
        $this->get('fos_user.user_manager')->updateUser($user);

        return $user;
    }

    /**
     * @param array $userData
     * @param User $user
     */
    private function createNetwork(array $userData, User $user)
    {
        $network = new Network();
        $network->setType('twitter');
        $network->setUser($user);
        $network->setNetworkId($userData['id']);
        $network->setRest(serialize($userData));
        $network->setCreatedAt(new \DateTime('now'));
        $network->setDisplay($userData['screen_name']);
        $this->getDoctrine()->getManager()->persist($network);
    }

    /**
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
    }
}