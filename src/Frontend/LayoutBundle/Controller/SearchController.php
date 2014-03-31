<?php

namespace Frontend\LayoutBundle\Controller;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\Security\Core\Exception;

class SearchController extends Controller{
    
    public function searchAction(){
        try{
            $text = $this->get('request')->request->get('text');
            
            return $this->render('FrontendLayoutBundle:Search:searchResults.html.twig',
                    array('text' => $text));
        } catch (Exception $ex) {
            return $this->render('FrontendLayoutBundle:Search:searchResults.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
}
