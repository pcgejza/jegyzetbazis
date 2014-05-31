<?php

namespace Frontend\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/*
 * A főoldalhoz szükséges controller
 */
class DefaultController extends Controller{
    /*
     * A főoldal renderelése
     */
    public function indexAction(){
        
        $text = $this->getDoctrine()->getRepository('FrontendDocsBundle:Docs')
                ->findOneBySlug('index');
        
        return $this->render('FrontendIndexBundle:Default:index.html.twig', array(
            'text' => $text->getText()
        ));
    }
}
