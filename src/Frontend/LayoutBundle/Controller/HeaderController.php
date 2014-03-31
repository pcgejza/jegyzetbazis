<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Frontend\LayoutBundle\Controller;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse ;

/**
 * Description of HeaderController
 *
 * @author Erik
 */
class HeaderController extends Controller{
    
    
    public function getHeaderAction(){
        return $this->render('FrontendLayoutBundle:Header:header.html.twig', array());
    }
    
    public function getAuthRevealAction(){
        try{
            $page = $this->get('request')->request->get('page');
            
            if($page == NULL) throw new Exception('Hiba a page-val');
            
            
            $html = $this->renderView('FrontendLayoutBundle:Widgets:reveal.authWindow.html.twig',
                    array(
                        'page' => $page
                    ));
            
            return new JsonResponse(array(
                'html' => $html
            ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err'=>$ex->getMessage()));
        }
    }
    
}
