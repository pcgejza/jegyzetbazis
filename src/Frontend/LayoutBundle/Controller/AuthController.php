<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;

use Frontend\LayoutBundle\Form\Type\RegistrationFormType;
use Frontend\AccountBundle\Entity\User;
use Frontend\AccountBundle\Entity\UserSettings;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class AuthController extends Controller{
    
    public function registrationAction(Request $request){
        try{
            $em = $this->getDoctrine()->getManager();

            $header = "HIBA";
            
            if ($request->getMethod() == 'POST') {
                $form = $request->request->get('fos_user_registration_form');

                $name = $form['name'];
                $email = $form['email'];
                $nickname = $form['nickname'];
                $password = $form['password']['first'];
                $school = $form['school'];
                $username = (strlen($nickname)>0) ? $nickname : $email;

                $user = new User();
                $user->setUsername($username);
                $user->setPlainPassword($password);
                $user->setEmail($email);
                $em->persist($user);

                $UserSettings = new UserSettings();
                $UserSettings->setUser($user);
                $UserSettings->setName($name);
                $em->persist($UserSettings);
                $em->flush();
                
                
                $header = $this->loginSet($user);
            }

            return new JsonResponse(array('header' => $header));
        }catch(Exception $ex){
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    public function loginAction(Request $request){
        try{
            
            $userManager = $this->get('fos_user.user_manager');
            // FIXME: Ez így még nem jó!
            $name = $request->request->get('_username');
            $pass = $request->request->get('_password');
            
            
            $u = $this->container->get('security.context')->getToken()->getUser();
            
            if(is_object($u))
                throw new Exception('Már be vagy lépve \''.$u->getUserName().'\' userrel!');
            
            $User = $userManager->findUserByUsernameOrEmail($name);
            
            //felhasználónév ellenőrzés
            if($User === null)
                 throw new Exception('Hibás felhasználónév vagy email cím!');
             
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($User);
            $encoded_pass = $encoder->encodePassword($pass, $User->getSalt());
             
            // jelszó ellenőrzés
            if($User->getPassword() !== $encoded_pass){
                throw new Exception('Rossz a beírt jelszó!');
            }
            
            $header = $this->loginSet($User);
            
            return new JsonResponse(array('header' => $header));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    private function loginSet($User){
        $token = new UsernamePasswordToken($User, $User->getPassword(), 'main', $User->getRoles());
        $context = $this->get('security.context');
        $context->setToken($token);

        return $this->renderView('FrontendLayoutBundle:Header:header.html.twig',
                array('User'=>$User));
    }
    
    public function checkEmailAction(){
        try{
            $email = $this->get('request')->request->get('email');
            
            $cnt = $this->getDoctrine()->getRepository('FrontendAccountBundle:User')
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
            
            $cnt = $this->getDoctrine()->getRepository('FrontendAccountBundle:User')
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
