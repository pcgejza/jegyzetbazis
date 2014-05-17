<?php

namespace Frontend\DocsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;

class DefaultController extends Controller
{
    public function indexAction($slug){
        try{
            $Docs = $this->getDoctrine()->getRepository('FrontendDocsBundle:Docs')
                        ->findOneBySlug($slug);
            
            if($Docs === NULL){
                throw new Exception('Nincs ilyen dokumentum!');
            }
            
            $returnArr = array('Doc' => $Docs);
        } catch (Exception $ex) {
            $returnArr = array('err' => $ex->getMessage());
        }
        
        
        return $this->render('FrontendDocsBundle:Default:index.html.twig', $returnArr);
    }
}
