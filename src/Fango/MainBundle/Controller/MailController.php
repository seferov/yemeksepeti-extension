<?php

namespace Fango\MainBundle\Controller;

use Fango\MainBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MailController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class MailController extends Controller
{
    /**
     * @param $uid
     * @return Response
     */
    public function unsubscribeAction($uid)
    {
        $message = '';
        if ($uid) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Fango\MainBundle\Entity\Mail $mail */
            $mail = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $uid]);

            if ($mail instanceof Mail) {
                $mail->setSubscribed(false);
                $em->persist($mail);
                $em->flush();
                $em->clear();
                $message = 'You have successfully unsubscribed!';
            }
        }

        return new Response($message);
    }
}
