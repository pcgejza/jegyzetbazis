<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendLayoutBundle:Default:index.html.twig', array('name' => $name));
    }
}
