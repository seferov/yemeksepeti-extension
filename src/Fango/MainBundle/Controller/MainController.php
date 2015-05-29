<?php

namespace Fango\MainBundle\Controller;

use Fango\MainBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MainController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class MainController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if ($request->get('uid')) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Fango\MainBundle\Entity\Mail $mail */
            $mail = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $request->get('uid')]);

            if ($mail instanceof Mail) {
                $mail->setLinkClicked(true);
                $mail->setIpAdress($request->getClientIp());
                $em->persist($mail);
                $em->flush();
                $em->clear();
            }
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('fango_main_dashboard');
        }

        return $this->render('FangoMainBundle::index.html.twig');
    }
}
