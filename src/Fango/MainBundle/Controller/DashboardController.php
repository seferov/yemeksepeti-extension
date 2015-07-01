<?php

namespace Fango\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class DashboardController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class DashboardController extends DashboardBaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
//        return $this->render('@FangoMain/Dashboard/index.html.twig');
        return $this->redirectToRoute('fango_main_campaign_list');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function earningsAction()
    {
        return $this->render('@FangoMain/Dashboard/earnings.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentAction(Request $request)
    {
        /** @var \Fango\UserBundle\Entity\User $user */
        $user = $this->getUser();

        if ('POST' == $request->getMethod()) {
            $user->setPaypalEmail($request->get('paypal'));
            $errors = $this->get('validator')->validate($user);

            if (count($errors) < 1) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Your info was successfully saved!');
            }
            else {
                $this->addFlash('error', $errors);
            }
        }

        return $this->render('@FangoMain/Dashboard/payment.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function banAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('FangoUserBundle:User')->find($request->get('id'));

            if ($user) {
                $user->setLocked(true);

                $stmt = $em->getConnection()->prepare("update fg_transaction t
                    join fg_user_campaign uc on t.`user_campaign_id` = uc.`id`
                    join fg_user u on u.`id` = uc.`user_id` and u.`id` = 1
                set t.disabled = 1");
                $stmt->execute();

                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'User was permanently banned!');
            }
            else {
                $this->addFlash('error', 'User not found!');
            }
        }

        return $this->render('@FangoMain/Dashboard/ban.html.twig');
    }
}
