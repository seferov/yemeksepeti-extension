<?php

namespace Fango\UserBundle\Controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Fango\UserBundle\Entity\User;
use Fango\UserBundle\Helper\UserHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class TwitterController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
class TwitterController extends BaseSocialController
{
    public function getType()
    {
        return 'twitter';
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Abraham\TwitterOAuth\TwitterOAuthException
     */
    public function loginAction()
    {
        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'));
        $requestToken = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->generateUrl('fango_user_twitter_check', [], true)));
        $url = $connection->url('oauth/authenticate', array('oauth_token' => $requestToken['oauth_token']));

        return $this->redirect($url);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Abraham\TwitterOAuth\TwitterOAuthException
     */
    public function checkAction(Request $request)
    {
        if ($request->get('denied')) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'));
        $content = $connection->oauth('oauth/access_token', [
            'oauth_verifier' => $request->get('oauth_verifier'),
            'oauth_token' => $request->get('oauth_token')
        ]);

        $connection = new TwitterOAuth($this->container->getParameter('twitter_client'), $this->container->getParameter('twitter_secret'), $content['oauth_token'], $content['oauth_token_secret']);
        $userData = $connection->get("account/verify_credentials");

        $userData = json_decode(json_encode($userData), true);
        $userData['token'] = $content['oauth_token'];
        $userData['token_secret'] = $content['oauth_token_secret'];
        $userData['display'] = $userData['screen_name'];

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

        $this->authenticateUser($user, $userData);

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
}