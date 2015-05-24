<?php

namespace Fango\MainBundle\Controller;

/**
 * Class NetworkController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class NetworkController extends DashboardBaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $networks = $this->getDoctrine()
            ->getManager()
            ->getRepository('FangoUserBundle:Network')
            ->findBy(['user' => $this->getUser()]);

        return $this->render('@FangoMain/Networks/index.html.twig', [
            'networks' => $networks
        ]);
    }
}
