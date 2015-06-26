<?php

namespace Fango\UserBundle\Controller;

use Fango\UserBundle\Entity\User;
use Fango\UserBundle\Helper\UserHelper;
use MetzWeb\Instagram\Instagram;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InstagramController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Controller
 */
class InstagramController extends BaseSocialController
{
    public function getType()
    {
        return 'instagram';
    }

    public function loginAction()
    {
        $instagram = new Instagram(array(
            'apiKey'      => $this->container->getParameter('instagram_client_id'),
            'apiSecret'   => $this->container->getParameter('instagram_client_secret'),
            'apiCallback' => $this->generateUrl('fango_user_instagram_check', [], true)
        ));

        return $this->redirect($instagram->getLoginUrl());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkAction(Request $request)
    {
        $code = $request->get('code');
        $instagram = new Instagram(array(
            'apiKey'      => $this->container->getParameter('instagram_client_id'),
            'apiSecret'   => $this->container->getParameter('instagram_client_secret'),
            'apiCallback' => $this->generateUrl('fango_user_instagram_check', [], true)
        ));
        $userData = $instagram->getOAuthToken($code);
        $userData = json_decode(json_encode($userData), true);
        $userData = $userData['user'];
        $userData['token'] = $code;
        $userData['display'] = $userData['username'];

        if (!array_key_exists('id', $userData)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FangoUserBundle:User')->getUserBySocialId($userData['id'], 'instagram');

        if (!$user) {
            $user = $em->getRepository('FangoUserBundle:User')->findOneBy([
                'username' => $this->generateUsername($userData)
            ]);
        }

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
        $user->setUsername($this->generateUsername($userData));
        $user->setEmail('none');
        $user->setFullname($userData['full_name']);

        $this->createNetwork($userData, $user);

        $user = UserHelper::fillDefaultValues($user);

        $user->setPlainPassword('randomPass');
        $this->get('fos_user.user_manager')->updateUser($user);

        return $user;
    }

    private function generateUsername(array $userData)
    {
        return $userData['username'].$userData['id'];
    }
}