<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingsController extends Controller {
    
    
    public function indexAction($subject = NULL)
    {
        if($this->get('request')->getMethod() === 'POST'){
            return $this->getSubjectAction($subject);
        }
        
        return $this->render('FrontendSubjectBundle:Default:index.html.twig',
                array('subject'=>$subject));
    }
    
    
    
    public function settingsIndexAction($page = NULL){
        if(!$this->get('security.context')->isGranted('ROLE_USER')){
            // ha nincs belépve vissza irányítom a főoldalra
            return $this->redirect($this->generateUrl('frontend_index_homepage'));
        }
        
        if($this->get('request')->getMethod() === 'POST'){
            return $this->getSubPageAction($page);
        }
        
        return $this->render('FrontendAccountBundle:Settings:index.html.twig',array(
                'page' => $page,
                'title' => $this->getSubPageTitle($page)
                ));
    }
    
    public function getSubPageTitle($page){
        switch($page){
            case 'alap-beallitasok': return 'Alap beállítások'; break;
            case 'biztonsagi-beallitasok': return 'Biztonsági beállítások'; break;
            case 'avatar-beallitasok': return 'Avatár beállítások'; break;
            case 'megjegyzes-beallitasok': return 'Megyjezés'; break;
            default : return null;
        }
    }
    
    public function getSubPageAction($page = null){
        if(!$this->get('security.context')->isGranted('ROLE_USER')){
            // ha nincs belépve vissza irányítom a főoldalra
            return $this->redirect($this->generateUrl('frontend_index_homepage'));
        }
        
        switch ($page){
            case 'alap-beallitasok':
                return $this->baseSettingsAction();
                break;
            default: 
                return new \Symfony\Component\HttpFoundation\Response('Hiba : Nincs ilyen oldal!');
        }
        
        return $this->testtSubPage($page);
    }
    
    public function testtSubPage($page = null){
        return $this->render('FrontendAccountBundle:Settings:tesztpage.html.twig',
                array('data' => $page));
    }
    
    public function baseSettingsAction(){
        return $this->render('FrontendAccountBundle:Settings:baseSettings.html.twig');
    }
    
    
}
