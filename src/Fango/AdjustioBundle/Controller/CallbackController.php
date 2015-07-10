<?php

namespace Fango\AdjustioBundle\Controller;

use Fango\MainBundle\Entity\WebhookLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CallbackController extends Controller
{
    private static $ipAddresses = [
        '178.162.216.64',
        '178.162.216.65',
        '178.162.216.66',
        '178.162.216.67',
        '178.162.216.68',
        '178.162.216.69',
        '178.162.216.70',
        '178.162.216.71',
        '178.162.216.72',
        '178.162.216.73',
        '178.162.216.74',
        '178.162.216.75',
        '178.162.216.76',
        '178.162.216.77',
        '178.162.216.78',
        '178.162.216.79',
        '178.162.216.80',
        '178.162.216.81',
        '178.162.216.82',
        '178.162.216.83',
        '178.162.216.84',
        '178.162.216.85',
        '178.162.216.86',
        '178.162.216.87',
        '178.162.216.88',
        '178.162.216.89',
        '178.162.216.90',
        '178.162.216.91',
        '178.162.216.92',
        '178.162.216.93',
        '178.162.216.94',
        '178.162.216.95',
        '178.162.216.96',
        '178.162.216.97',
        '178.162.216.98',
        '178.162.216.99',
        '178.162.216.100',
        '178.162.216.101',
        '178.162.216.102',
        '178.162.216.103',
        '178.162.216.104',
        '178.162.216.105',
        '178.162.216.106',
        '178.162.216.107',
        '178.162.216.108',
        '178.162.216.109',
        '178.162.216.110',
        '178.162.216.111',
        '178.162.216.112',
        '178.162.216.113',
        '178.162.216.114',
        '178.162.216.115',
        '178.162.216.116',
        '178.162.216.117',
        '178.162.216.118',
        '178.162.216.119',
        '178.162.216.120',
        '178.162.216.121',
        '178.162.216.122',
        '178.162.216.123',
        '178.162.216.124',
        '178.162.216.125',
        '178.162.216.126',
        '178.162.216.127',
        '178.162.216.128',
        '178.162.216.129',
        '178.162.216.130',
        '178.162.216.131',
        '178.162.216.132',
        '178.162.216.133',
        '178.162.216.134',
        '178.162.216.135',
        '178.162.216.136',
        '178.162.216.137',
        '178.162.216.138',
        '178.162.216.139',
        '178.162.216.140',
        '178.162.216.141',
        '178.162.216.142',
        '178.162.216.143',
        '178.162.216.144',
        '178.162.216.145',
        '178.162.216.146',
        '178.162.216.147',
        '178.162.216.148',
        '178.162.216.149',
        '178.162.216.150',
        '178.162.216.151',
        '178.162.216.152',
        '178.162.216.153',
        '178.162.216.154',
        '178.162.216.155',
        '178.162.216.156',
        '178.162.216.157',
        '178.162.216.158',
        '178.162.216.159',
        '178.162.216.160',
        '178.162.216.161',
        '178.162.216.162',
        '178.162.216.163',
        '178.162.216.164',
        '178.162.216.165',
        '178.162.216.166',
        '178.162.216.167',
        '178.162.216.168',
        '178.162.216.169',
        '178.162.216.170',
        '178.162.216.171',
        '178.162.216.172',
        '178.162.216.173',
        '178.162.216.174',
        '178.162.216.175',
        '178.162.216.176',
        '178.162.216.177',
        '178.162.216.178',
        '178.162.216.179',
        '178.162.216.180',
        '178.162.216.181',
        '178.162.216.182',
        '178.162.216.183',
        '178.162.216.184',
        '178.162.216.185',
        '178.162.216.186',
        '178.162.216.187',
        '178.162.216.188',
        '178.162.216.189',
        '178.162.216.190',
        '178.162.216.191',
    ];

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $trans = $request->query->get('trans');

        // Check if the request come from adjust.io or not
        $fake = !in_array($request->getClientIp(), self::$ipAddresses);

        // Log the request
        $log = new WebhookLog();
        $log->setCreatedAt(new \DateTime('now'));
        $log->setIpAddress($request->getClientIp());
        $log->setUserAgent($request->headers->get('User-Agent'));
        $log->setTransaction($trans);
        $log->setFake($fake);

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

        $isEarned = !$fake;

        $transaction->setAction($isEarned);
        $em->persist($transaction);

        if ($isEarned) {
            $user = $transaction->getUserCampaign()->getUser();
            $user->setEarnings($user->getEarnings() + $transaction->getUserCampaign()->getCampaign()->getCpa());
            $em->persist($user);
        }

        $em->flush();
        $em->clear();

        if ($fake) {
            throw $this->createNotFoundException();
        }

        return $response->setData([
            'success' => true
        ]);
    }
}
