<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontend\AccountBundle\Form\Type\BaseSettingsFormType;

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
        $request = $this->get('request');
        $User = $this->get('security.context')->getToken()->getUser();
        
        
        $BaseSettingsForm = new BaseSettingsFormType();
        $BaseSettingsForm->setUser($User);
        
        $url = $this->generateUrl('base_settings_save');
        
        $form = $this->createForm($BaseSettingsForm, null, array(
            'action' => $url,
            'method' => 'POST',
            'attr' => array('class' => 'baseSettingsForm settings-form')
        ));
        
        if($request->getMethod() === 'POST'){   
            $form->bind($request);
            if($form->isValid()){
                $formData = $form->getData();
                $name = $formData['name'];
                $gender = $formData['gender'];
                $UserSettings = $User->getUserSettings();
                if($UserSettings == NULL){
                    $UserSettings->setUser($User);
                }
                $UserSettings->setName($name);
                $UserSettings->setGender($gender);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($UserSettings);
                $em->flush();
            }
        }
        
        return $this->render('FrontendAccountBundle:Forms:baseSettingsForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
    
    
    
}
