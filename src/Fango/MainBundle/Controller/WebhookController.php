<?php

namespace Fango\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WebhookController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();

        /** @var \Fango\MainBundle\Entity\Transaction $transaction */
        $transaction = $em->getRepository('FangoMainBundle:Transaction')->findOneBy([
            'hash' => $request->query->get('trans')
        ]);

        if (!$transaction) {
            return $response->setData(['error' => [
                'code' => 404,
                'message' => 'Transaction not found'
            ]]);
        }

        if ($transaction->getAction()) {
            return $response->setData(['error' => [
                'code' => 187,
                'message' => 'Duplicate call'
            ]]);
        }

        $transaction->setAction(true);

        $em->persist($transaction);
        $em->flush();
        $em->clear();

        return $response->setData(['success' => true]);
    }
}