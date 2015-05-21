<?php

namespace Fango\MainBundle\Controller;

use Fango\MainBundle\Entity\Transaction;
use Fango\MainBundle\Entity\UserCampaign;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CampaignController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class CampaignController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $campaigns = $this->getDoctrine()
            ->getManager()
            ->getRepository('FangoMainBundle:Campaign')
            ->findAll();

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
        $campaign = $em->getRepository('FangoMainBundle:Campaign')->find($id);
        $userCampaign = $em->getRepository('FangoMainBundle:UserCampaign')->findOneBy([
            'campaign' => $campaign,
            'user' => $this->getUser()
        ]);

        return $this->render('@FangoMain/Campaign/show.html.twig', [
            'campaign' => $campaign,
            'userCampaign' => $userCampaign
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function applyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $campaign = $em->getRepository('FangoMainBundle:Campaign')->find($id);
        $userCampaign = $em->getRepository('FangoMainBundle:UserCampaign')->findOneBy([
            'campaign' => $campaign,
            'user' => $this->getUser()
        ]);

        if ($userCampaign && $userCampaign instanceof UserCampaign) {
            $this->addFlash('notice', 'You already have applied for this campaign');
            return $this->redirectToRoute('fango_main_campaign_show', ['id' => $id]);
        }

        $userCampaign = new UserCampaign();
        $userCampaign->setCampaign($campaign);
        $userCampaign->setUser($this->getUser());
        $lastUniqueLink = $em->getRepository('FangoMainBundle:UserCampaign')->getLastLink();
        $userCampaign->setUniqueLink($this->get('fango_main.unique_link_generator')->getNextUniqueLink($lastUniqueLink));

        $em->persist($userCampaign);
        $em->flush();
        $em->clear();

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

        $transaction = new Transaction();
        $transaction->setCreatedAt(new \DateTime('now'));
        $transaction->setIpAddress($request->getClientIp());
        $transaction->setAction(false);
        $transaction->setUserCampaign($userCampaign);
        $transaction->setReferer($request->headers->get('referer'));
        $hash = $this->get('fos_user.util.token_generator')->generateToken();
        $transaction->setHash($hash);

        $em->persist($transaction);
        $em->flush();
        $em->clear();

        return $this->redirect($userCampaign->getCampaign()->getActionLink().'?trans='.$hash);
    }
}
