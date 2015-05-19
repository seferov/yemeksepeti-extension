<?php

namespace Fango\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhookController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $transaction = $em->getRepository('FangoMainBundle:Transaction')->findOneBy([
            'hash' => $request->query->get('trans')
        ]);

        if (!$transaction) {
            throw $this->createNotFoundException();
        }

        $transaction->setActionCount($transaction->getActionCount() + 1);

        $em->persist($transaction);
        $em->flush();
        $em->clear();

        return new Response();
    }
}