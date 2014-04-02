<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;

use Frontend\LayoutBundle\Form\Type\RegistrationFormType;
use Frontend\LayoutBundle\Entity\User;

class AuthController extends Controller{
    
    public function registrationAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        
        if ($request->getMethod() == 'POST') {
            $form = $request->request->get('fos_user_registration_form');
           
            $name = $form['name'];
            $email = $form['email'];
            $nickname = $form['nickname'];
            $password = $form['password']['first'];
            $university = $form['university'];
            
            $username = (strlen($nickname)>0) ? $nickname : $email;
            
            $user = new User();
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setEmail($email);
            
            $em->persist($user);
            $em->flush();
        }
        
        return new JsonResponse(array('ok' => $user->getId()));
    }
    
    public function loginAction(){
        
    }
    
}
