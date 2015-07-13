<?php

namespace Fango\UserBundle\Controller;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Fango\MainBundle\Helper\Utils;
use Fango\UserBundle\Entity\Network;
use Fango\UserBundle\Entity\User;
use Fango\UserBundle\Helper\UserHelper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FacebookController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
class FacebookController extends BaseSocialController
{
    public function getType()
    {
        return 'facebook';
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(Request $request)
    {
        FacebookSession::setDefaultApplication($this->container->getParameter('facebook_app_id'), $this->container->getParameter('facebook_app_secret'));
        $helper = new FacebookRedirectLoginHelper($this->generateUrl('fango_user_facebook_auth', ['host' => $request->get('site')['host']], true));

        return $this->redirect($helper->getLoginUrl(['email', 'manage_pages']));
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

        $userData['token'] = $session->getToken();
        $userData['display'] = $userData['first_name'].' '.$userData['last_name'];

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FangoUserBundle:User')->getUserBySocialId($userData['id'], 'facebook');

        if (!$user) {
            $user = $em->getRepository('FangoUserBundle:User')->findOneBy([
                'email' => $userData['email']
            ]);

            if (!$user) {
                if ($this->getUser()) {
                    $user = $this->getUser();
                    $network = $this->createNetwork($userData, $user);
                }
                else {
                    $user = $this->createUser($userData);
                    $network = $user->getNetworks()[0];
                    $em->persist($user);
                }
            }
            else {
                $network = $this->createNetwork($userData, $user);
            }

            $em->persist($user);
            $em->flush();
        }

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('notice', 'You\'ve successfully connected your account. Please enter fees per post.');

            if (isset($network) && $network instanceof Network) {
                return $this->redirectToRoute('fango_dashboard_network_edit', [
                    'id' => $network->getId()
                ]);
            }

            return $this->redirectToRoute('fango_dashboard_networks_index');
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
     * @return mixed
     *
     */
    private function generateUsername(array $userData)
    {
        $username = str_replace(' ', '', $userData['first_name'].$userData['last_name'].substr($userData['id'], -4));

        return Utils::slugify($username);
    }
}