<?php

namespace Frontend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class DefaultController extends Controller
{
    public function loginAction()
    {
        $request = $this->get('request');
        
        $returnArray = array();
        
        
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === true){
            $url = $this->generateUrl('admin_list');
            return $this->redirect($url);
        }
        
        if($request->isMethod('POST')){
            try{  
                $email = $request->request->get('email');
                $pass = $request->request->get('pass');
                $userManager = $this->get('fos_user.user_manager');
                $User = $userManager->findUserByUsernameOrEmail($email);

                if($User === null)
                   throw new Exception('Hibás email cím vagy rossz a beírt jelszó');

                $encoder_service = $this->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($User);
                $encoded_pass = $encoder->encodePassword($pass, $User->getSalt());

                // jelszó ellenőrzés
                if($User->getPassword() !== $encoded_pass){
                    throw new Exception('Hibás email cím vagy rossz a beírt jelszó');
                }
                $token = new UsernamePasswordToken($User, $User->getPassword(), 'main', $User->getRoles());
                $context = $this->get('security.context');
                $context->setToken($token);
            } catch (Exception $ex) {
                $returnArray['err'] = $ex->getMessage();
            }
        }
        
        
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === true){
            $url = $this->generateUrl('admin_list');
            return $this->redirect($url);
        }
        
        if ($this->get('security.context')->isGranted('ROLE_USER') === true){
            $returnArray['err'] = 'Nincs jogosultságod az adminhoz, mert te csak egy sima felhasználó vagy!';
        }
            
        return $this->render('FrontendAdminBundle:Default:index.html.twig', $returnArray);
    }
    
    public function listAction(){
        
        return $this->render('FrontendAdminBundle:Default:list.html.twig');
    }
}
