<?php

namespace Frontend\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendMessagingBundle:Default:index.html.twig', array('name' => $name));
    }
}
