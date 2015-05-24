<?php

namespace Fango\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class AccountController extends DashboardBaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enterMailAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->getEmail() != 'none') {
            return $this->redirectToRoute('fango_main_dashboard');
        }

        if ('POST' == $request->getMethod()) {
            $user->setEmail($request->get('email'));
            $em = $this->getDoctrine()->getManager();

            $this->get('fos_user.user_manager')->updateUser($user);
            $em->persist($user);
            $em->flush();
            $em->clear();

            return $this->redirectToRoute('fango_main_dashboard');
        }
        return $this->render('@FangoMain/Account/enterMail.html.twig');
    }
}