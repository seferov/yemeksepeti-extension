<?php

namespace Fango\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 * @package Fango\MainBundle\Controller
 */
class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('@FangoMain/Dashboard/index.html.twig');
    }
}
