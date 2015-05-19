<?php

namespace Fango\MainBundle\Controller;

use Fango\MainBundle\Entity\UserCampaign;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $userCampaign->setUniqueLink('1');

        $em->persist($userCampaign);
        $em->flush();
        $em->clear();

        $this->addFlash('notice', 'You have successfully applied for this campaign!');

        return $this->redirectToRoute('fango_main_campaign_show', ['id' => $id]);
    }
}