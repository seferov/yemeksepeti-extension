<?php

namespace Fango\UserBundle\Controller;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Fango\MainBundle\Helper\Utils;
use Fango\UserBundle\Entity\Network;
use Fango\UserBundle\Entity\User;
use Fango\UserBundle\Helper\UserHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class FacebookController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
class FacebookController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(Request $request)
    {
        FacebookSession::setDefaultApplication($this->container->getParameter('facebook_app_id'), $this->container->getParameter('facebook_app_secret'));
        $helper = new FacebookRedirectLoginHelper($this->generateUrl('fango_user_facebook_auth', ['host' => $request->get('site')['host']], true));

        return $this->redirect($helper->getLoginUrl(['email']));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Facebook\FacebookRequestException
     */
    public function authAction(Request $request)
    {
        if ($request->get('error')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        FacebookSession::setDefaultApplication($this->container->getParameter('facebook_app_id'), $this->container->getParameter('facebook_app_secret'));
        $params = array(
            'client_id' => FacebookSession::_getTargetAppId(),
            'redirect_uri' => $this->generateUrl('fango_user_facebook_auth', ['host' => $request->get('host')], true),
            'client_secret' =>
                FacebookSession::_getTargetAppSecret(),
            'code' => $request->get('code')
        );

        $response = (new FacebookRequest(
            FacebookSession::newAppSession($this->container->getParameter('facebook_app_id'), $this->container->getParameter('facebook_app_secret')),
            'GET',
            '/oauth/access_token',
            $params
        ))->execute()->getResponse();

        return $this->redirect($this->generateUrl('fango_user_facebook_check').'?'.http_build_query(['access_token' => $response['access_token']]));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Facebook\FacebookRequestException
     */
    public function checkAction(Request $request)
    {
        $session = new FacebookSession($request->get('access_token'));

        $request = new FacebookRequest($session, 'GET', '/me');
        $response = $request->execute();
        $userData = $response->getGraphObject()->asArray();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FangoUserBundle:User')->getUserBySocialId($userData['id'], 'facebook');

        if (!$user) {
            $user = $em->getRepository('FangoUserBundle:User')->findOneBy([
                'email' => $userData['email']
            ]);

            if (!$user) {
                if ($this->getUser()) {
                    $user = $this->getUser();
                }
                else {
                    $user = $this->createUser($userData);
                    $em->persist($user);
                }
            }

            $this->createNetwork($userData, $user);

            $em->persist($user);
            $em->flush();
        }

        $this->authenticateUser($user);

        return $this->redirectToRoute('fango_main_dashboard');
    }

    /**
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * @param array $userData
     * @return User
     */
    private function createUser(array $userData)
    {
        $user = new User();
        $user->setUsername($this->generateUsername($userData));
        $user->setEmail($userData['email']);
        $user->setFullname($userData['first_name'].' '.$userData['last_name']);

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
        $network->setType('facebook');
        $network->setUser($user);
        $network->setNetworkId($userData['id']);
        $network->setRest(serialize($userData));
        $network->setCreatedAt(new \DateTime('now'));
        $network->setDisplay($userData['first_name'].' '.$userData['last_name']);
        $this->getDoctrine()->getManager()->persist($network);
    }

    /**
     * @param array $userData
     * @return mixed
     *
     */
    private function generateUsername(array $userData)
    {
        $username = str_replace(' ', '', $userData['first_name'].$userData['last_name'].substr($userData['id'], -4));

        return Utils::slugify($username);
    }
}