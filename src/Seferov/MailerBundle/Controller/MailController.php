<?php

namespace Seferov\MailerBundle\Controller;

use Seferov\MailerBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MailController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\MailerBundle\Controller
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
        $batch = $em->getRepository('SeferovMailerBundle:Mail\Batch')->findOneBy(['uid' => $uid]);

        if ($batch instanceof Mail\Batch) {
            $mail = $batch->getMail();
            $mail->setUnsubscribed(true);
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
            $batch = $em->getRepository('SeferovMailerBundle:Mail\Batch')->findOneBy(['uid' => $uid]);

            if ($batch instanceof Mail\Batch) {
                $mail = $batch->getMail();
                $mail->setUnsubscribed(false);
                $em->persist($mail);
                $em->flush();
                $em->clear();
                $message = 'You have successfully resubscribed!';
            }
        }

        return new Response($message);
    }
}
