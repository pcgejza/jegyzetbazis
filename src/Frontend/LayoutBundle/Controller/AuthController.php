<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;

use Frontend\LayoutBundle\Form\Type\RegistrationFormType;
use Frontend\LayoutBundle\Entity\User;

class AuthController extends Controller{
    
    public function registrationAction(Request $request){
        try{
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
        }catch(Exception $ex){
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    public function loginAction(){
        
    }
    
    public function checkEmailAction(){
        try{
            $email = $this->get('request')->request->get('email');
            
            $cnt = $this->getDoctrine()->getRepository('FrontendLayoutBundle:User')
                    ->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getSingleScalarResult();
            
            return new JsonResponse(array(
                'exist'=> ($cnt>0) ? true : false 
                ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    public function checkNicknameAction(){
        try{
            $nickname = $this->get('request')->request->get('nickname');
            
            $cnt = $this->getDoctrine()->getRepository('FrontendLayoutBundle:User')
                    ->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.username = :nickname')
                    ->setParameter('nickname', $nickname)
                    ->getQuery()
                    ->getSingleScalarResult();
            
            return new JsonResponse(array(
                'exist'=> ($cnt>0) ? true : false 
                ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
}
