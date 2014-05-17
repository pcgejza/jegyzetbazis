<?php

namespace Frontend\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $text = $this->getDoctrine()->getRepository('FrontendDocsBundle:Docs')
                ->findOneBySlug('index');
        
        
        return $this->render('FrontendIndexBundle:Default:index.html.twig', array(
            'text' => $text->getText()
        ));
    }
}
