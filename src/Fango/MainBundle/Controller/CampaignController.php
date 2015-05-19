<?php

namespace Fango\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CampaignController
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
        $campaign = $this->getDoctrine()
            ->getManager()
            ->getRepository('FangoMainBundle:Campaign')
            ->find($id);

        return $this->render('@FangoMain/Campaign/show.html.twig', [
            'campaign' => $campaign
        ]);
    }

    public function applyAction($id)
    {
        
    }
}