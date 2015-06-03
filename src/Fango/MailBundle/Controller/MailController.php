<?php

namespace Fango\MailBundle\Controller;

use Fango\MainBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MailController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MailBundle\Controller
 */
class MailController extends Controller
{
    /**
     * @param $uid
     * @return Response
     */
    public function unsubscribeAction($uid)
    {
        if (!$uid) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        /** @var \Fango\MainBundle\Entity\Mail $mail */
        $mail = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $uid]);

        if ($mail instanceof Mail) {
            $mail->setSubscribed(false);
            $em->persist($mail);
            $em->flush();
            $em->clear();
        }
        else {
            throw $this->createNotFoundException();
        }

        return $this->render('@FangoMail/unsubscribed.html.twig', [
            'uid' => $uid
        ]);
    }

    /**
     * @param $uid
     * @return Response
     */
    public function resubscribeAction($uid)
    {
        $message = '';
        if ($uid) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Fango\MainBundle\Entity\Mail $mail */
            $mail = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $uid]);

            if ($mail instanceof Mail) {
                $mail->setSubscribed(true);
                $em->persist($mail);
                $em->flush();
                $em->clear();
                $message = 'You have successfully resubscribed!';
            }
        }

        return new Response($message);
    }
}
