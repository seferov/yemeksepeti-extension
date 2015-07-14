<?php

namespace Fango\MainBundle\Controller;

use Fango\MainBundle\Entity\Transaction;
use Fango\MainBundle\Entity\UserCampaign;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CampaignController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class CampaignController extends DashboardBaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $campaigns = $this->getDoctrine()
            ->getManager()
            ->getRepository('FangoMainBundle:Campaign')
            ->createQueryBuilder('c')
            ->where('c.status = :status')
            ->orWhere('c.userId = :userId')
            ->setParameters([
                'status' => 'published',
                'userId' => $this->getUser()->getId()
            ])
            ->getQuery()
            ->getResult();

        return $this->render('@FangoMain/Campaign/list.html.twig', [
            'campaigns' => $campaigns
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $campaign = $this->getCampaign($id);

        if (!$campaign) {
            throw $this->createNotFoundException();
        }

        $userCampaign = $em->getRepository('FangoMainBundle:UserCampaign')->findOneBy([
            'campaign' => $campaign,
            'user' => $this->getUser(),
            'status' => 'applied'
        ]);

        return $this->render('@FangoMain/Campaign/show.html.twig', [
            'campaign' => $campaign,
            'userCampaign' => $userCampaign
        ]);
    }

    /**
     * @param $id
     * @param $status (applied|preview)
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function applyAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $campaign = $this->getCampaign($id);

        $user = $status == 'preview'
            ? $em->getRepository('FangoUserBundle:User')->find(11)
            : $this->getUser();

        $userCampaign = $em->getRepository('FangoMainBundle:UserCampaign')->findOneBy([
            'campaign' => $campaign,
            'user' => $user,
            'status' => $status
        ]);

        if ($userCampaign && $userCampaign instanceof UserCampaign) {
            if ($userCampaign->getStatus() == 'preview') {
                return $this->redirectToRoute('fango_main_short_link', [
                    'hash' => $userCampaign->getUniqueLink()
                ]);
            }

            $this->addFlash('notice', 'You already have applied for this campaign');
            return $this->redirectToRoute('fango_main_campaign_show', ['id' => $id]);
        }

        $userCampaign = new UserCampaign();
        $userCampaign->setCampaign($campaign);
        $userCampaign->setUser($user);
        $lastUniqueLink = $em->getRepository('FangoMainBundle:UserCampaign')->getLastLink();
        $userCampaign->setUniqueLink($this->get('fango_main.unique_link_generator')->getNextUniqueLink($lastUniqueLink));
        $userCampaign->setStatus($status);

        $em->persist($userCampaign);
        $em->flush();
        $em->clear();

        if ($status == 'preview') {
            return $this->redirectToRoute('fango_main_short_link', [
                'hash' => $userCampaign->getUniqueLink()
            ]);
        }

        $this->addFlash('notice', 'You have successfully applied for this campaign!');

        return $this->redirectToRoute('fango_main_campaign_show', ['id' => $id]);
    }

    /**
     * @param $hash
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function shortLinkAction($hash, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Fango\MainBundle\Entity\UserCampaign $userCampaign */
        $userCampaign = $em->getRepository('FangoMainBundle:UserCampaign')->findOneBy([
            'uniqueLink' => $hash
        ]);

        if (!$userCampaign) {
            throw $this->createNotFoundException();
        }

        // Redirect visitors coming from banned users' URLs to homepage
        if ($userCampaign->getUser()->isLocked()) {
            return $this->redirectToRoute('fango_main_homepage');
        }

        $url = $userCampaign->getCampaign()->getActionLink();

        // Filter bots
        if ($this->get('vipx_bot_detect.detector')->detectFromRequest($request)) {
            return $this->redirect($url, 301);
        }

        $isLocationSupported = false;
        // Geo location support
        $geoData = $this->container
            ->get('bazinga_geocoder.geocoder')
            ->using('ip_info_db')
            ->geocode($request->server->get('REMOTE_ADDR'));

        // Add as a new transaction
        $transaction = new Transaction();
        $transaction->setCreatedAt(new \DateTime('now'));
        $transaction->setIpAddress($request->getClientIp());
        $transaction->setAction(false);
        $transaction->setUserAgent($request->headers->get('User-Agent'));
        $transaction->setUserCampaign($userCampaign);
        $transaction->setReferer($request->headers->get('referer'));
        $hash = $this->get('fos_user.util.token_generator')->generateToken();
        $transaction->setHash($hash);
        $transaction->setCountry($geoData->getCountryCode());
        $em->persist($transaction);
        $em->flush();
        $em->clear();

        if (count($userCampaign->getCampaign()->getCountries())) {
            foreach ($userCampaign->getCampaign()->getCountries() as $country) {
                if ($country->getCountry() == $geoData->getCountryCode()) {
                    $isLocationSupported = true;
                    break;
                }
            }

            if (!$isLocationSupported && $userCampaign->getStatus() != 'preview') {
                return $this->redirectToRoute('fango_main_homepage');
            }
        }
        else {
            $isLocationSupported = true;
        }




        $url .= (parse_url($url, PHP_URL_QUERY)) ? '&' : '?';
        $url .= http_build_query([
            'trans' => $hash,
            'utm_source' => $userCampaign->getId()
        ]);

        if ($userCampaign->getStatus() == 'preview' && !$isLocationSupported) {
            $url = $userCampaign->getCampaign()->getPreviewLink();
        }

        return $this->redirect($url);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getCampaign($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('FangoMainBundle:Campaign')
            ->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
        ;

        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            if ($this->getUser()) {
                $qb->andWhere('c.status = :status or c.userId = :userId')
                    ->setParameter('status', 'published')
                    ->setParameter('userId', $this->getUser()->getId());
            }
            else {
                $qb->andWhere('c.status = :status')
                    ->setParameter('status', 'published');
            }
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
