<?php

namespace Seferov\MailerBundle\Controller;

use Seferov\MailerBundle\Entity\Mail;
use Seferov\MailerBundle\Http\TransparentPixelResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TrackingController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\MailerBundle\Controller
 */
class TrackingController extends Controller
{
    /**
     * @param Request $request
     * @return TransparentPixelResponse
     */
    public function emailOpenAction(Request $request)
    {
        $uid = $request->query->get('uid');
        if (null !== $uid) {
            $em = $this->getDoctrine()->getManager();
            $batch = $em->getRepository('SeferovMailerBundle:Mail\Batch')->findOneBy(['uid' => $uid]);

            if ($batch instanceof Mail\Batch) {
                $batch->setIsOpened(true);
                $em->persist($batch);
                $em->flush();
                $em->clear();
            }
        }

        return new TransparentPixelResponse();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectAction(Request $request)
    {
        $uid = $request->query->get('uid');
        if (null !== $uid) {
            $em = $this->getDoctrine()->getManager();
            $batch = $em->getRepository('SeferovMailerBundle:Mail\Batch')->findOneBy(['uid' => $uid]);

            if ($batch instanceof Mail\Batch) {
                $batch->setLinkClicked(true);
                $batch->setIpAddress($request->getClientIp());
                $em->persist($batch);
                $em->flush();
                $em->clear();
            }
        }

        $url = $request->query->has('url')
            ? $request->query->get('url')
            : $this->generateUrl($this->container->getParameter('seferov_mailer_config')['main_route']);

        return $this->redirect($url);
    }
}