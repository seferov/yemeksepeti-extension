<?php

namespace Fango\MainBundle\Controller;

use Aws\Sns\MessageValidator\Message;
use Aws\Sns\MessageValidator\MessageValidator;
use Fango\MainBundle\Entity\WebhookLog;
use Guzzle\Http\Client;
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
        $trans = $request->query->get('trans');

        // Log the request
        $log = new WebhookLog();
        $log->setCreatedAt(new \DateTime('now'));
        $log->setIpAddress($request->getClientIp());
        $log->setUserAgent($request->headers->get('User-Agent'));
        $log->setTransaction($trans);

        $em->persist($log);
        $em->flush();

        /** @var \Fango\MainBundle\Entity\Transaction $transaction */
        $transaction = $em->getRepository('FangoMainBundle:Transaction')->findOneBy([
            'hash' => $trans
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
        $user = $transaction->getUserCampaign()->getUser();
        $user->setEarnings($user->getEarnings() + $transaction->getUserCampaign()->getCampaign()->getCpa());

        $em->persist($transaction, $user);
        $em->flush();
        $em->clear();

        return $response->setData(['success' => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emailAction(Request $request)
    {
        if ('POST' !== $request->getMethod()) {
            return new JsonResponse();
        }

        $message = json_decode(file_get_contents('php://input'), true);

        if ($message['Type'] == 'SubscriptionConfirmation') {
            // Send a request to the SubscribeURL to complete subscription
            (new Client)->get($message['SubscribeURL'])->send();
            return new JsonResponse();
        }

        $logger = $this->get('logger');
        $logger->info('Email report');
        $logger->error(json_encode($message));

        return new JsonResponse();
    }
}