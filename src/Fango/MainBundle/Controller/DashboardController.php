<?php

namespace Fango\MainBundle\Controller;

/**
 * Class DashboardController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class DashboardController extends DashboardBaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
//        return $this->render('@FangoMain/Dashboard/index.html.twig');
        return $this->redirectToRoute('fango_main_campaign_list');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function earningsAction()
    {
        return $this->render('@FangoMain/Dashboard/earnings.html.twig');
    }
}
