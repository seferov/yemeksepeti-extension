<?php

namespace Fango\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardBaseController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Controller
 */
class DashboardBaseController extends Controller
{
    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if ($this->getUser()->getEmail() == 'none'
            && $this->get('request_stack')->getCurrentRequest()->get('_route') != 'fango_main_dashboard_account_entermail') {
            return $this->redirectToRoute('fango_main_dashboard_account_entermail');
        }

        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }
}