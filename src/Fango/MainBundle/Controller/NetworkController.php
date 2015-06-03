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

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $network = $em->getRepository('FangoUserBundle:Network')->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$network) {
            throw $this->createNotFoundException();
        }

        $em->remove($network);
        $em->flush();
        $em->clear();

        return $this->redirectToRoute('fango_dashboard_networks_index');
    }
}
