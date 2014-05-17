<?php

namespace Frontend\DocsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendDocsBundle:Default:index.html.twig', array('name' => $name));
    }
}
