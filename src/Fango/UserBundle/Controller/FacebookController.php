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
        $fbUser = $response->getGraphObject()->asArray();

        $userManager = $this->get('fos_user.user_manager');
//        $user = $userManager->findUserBy(['facebookId' => $fbUser['id']]);
//
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FangoUserBundle:User')->getUserByFacebookId($fbUser['id']);

        if (!$user) {
            $user = $userManager->findUserByEmail($fbUser['email']);
            if (!$user) {
                $user = $this->createUser($fbUser);
            }
            else {
                // User is registered but not by facebook login
                // Register facebook info as well

                $network = new Network();
                $network->setType('facebook');
                $network->setUser($user);
                $network->setNetworkId($fbUser['id']);
                $network->setRest(serialize($fbUser));

                $em->persist($network);
            }

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
     * @param array $fbUser
     * @return User
     */
    private function createUser(array $fbUser)
    {
        $user = new User();
        $user->setUsername($this->generateUsername($fbUser));
        $user->setEmail($fbUser['email']);
        $user->setFullname($fbUser['first_name'].' '.$fbUser['last_name']);

        $network = new Network();
        $network->setType('facebook');
        $network->setUser($user);
        $network->setNetworkId($fbUser['id']);
        $network->setRest(serialize($fbUser));
        $network->setCreatedAt(new \DateTime('now'));
        $this->getDoctrine()->getManager()->persist($network);

        $user = UserHelper::fillDefaultValues($user);

        $user->setPlainPassword('randomPass');
        $this->get('fos_user.user_manager')->updateUser($user);

        return $user;
    }

    /**
     * @param array $fbUser
     * @return mixed
     *
     */
    private function generateUsername(array $fbUser)
    {
        $username = str_replace(' ', '', $fbUser['first_name'].$fbUser['last_name'].substr($fbUser['id'], -4));

        return Utils::slugify($username);
    }
}