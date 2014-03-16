<?php

namespace Frontend\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontendIndexBundle:Default:index.html.twig', array());
    }
}
