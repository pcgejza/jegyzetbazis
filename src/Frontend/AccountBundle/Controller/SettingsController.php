<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontend\AccountBundle\Form\Type\BaseSettingsFormType;
use Frontend\AccountBundle\Form\Type\SafetySettingsFormType;
use Frontend\AccountBundle\Form\Type\CommentSettingsFormType;
use Frontend\AccountBundle\Form\Type\AvatarSettingsFormType;
use Frontend\AccountBundle\Form\Type\ChangePasswordFormType;

use Frontend\AccountBundle\Entity\Avatar;

use Frontend\AccountBundle\Entity\UserSettings;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller{
    
    public function settingsIndexAction($page = NULL){
        if(!$this->get('security.context')->isGranted('ROLE_USER')){
            // ha nincs belépve vissza irányítom a főoldalra
            return $this->redirect($this->generateUrl('frontend_index_homepage'));
        }
        
        if($page == NULL){
            $url = $this->generateUrl('settings_sub_page' ,array('page'=>'alap-beallitasok'));
            return $this->redirect($url);
        }else{
            if($this->getSubPageTitle($page)==NULL){
                throw new \ErrorException('Nincs ilyen beállítás oldal!');
            }
        }
        
        if($this->get('request')->getMethod() === 'GET' && $this->get('request')->query->get('fromAjax') != NULL){
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
            case 'jelszo-valtoztatas': return 'Jelszó megváltoztatása'; break;
            case 'avatar-beallitasok': return 'Avatár beállítások'; break;
            case 'megjegyzes': return 'Megyjezés'; break;
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
            case 'biztonsagi-beallitasok':
                return $this->safetySettingsAction();
                break;
            case 'jelszo-valtoztatas':
                return $this->changePasswordAction();
                break;
            case 'avatar-beallitasok':
                return $this->avatarSettingsAction();
                break;
            case 'megjegyzes':
                return $this->commentAction();
                break;
            default: 
                return new \Symfony\Component\HttpFoundation\Response('Hiba : Nincs ilyen oldal!');
        }
        
        return $this->testtSubPage($page);
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
                
                if(!$this->passwordControl($formData['password'], $User)){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Rossz a beírt jelszó, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                $em = $this->getDoctrine()->getEntityManager();
                $name = $formData['name'];
                $gender = $formData['gender'];
                $bithDate = $formData['birthDay'];
                $school = $formData['school'];
                
                $UserSettings = $User->getUserSettings();
                if($UserSettings == NULL){
                    $UserSettings = new UserSettings();
                    $UserSettings->setUser($User);
                }
                   
                if($school != NULL && strlen($school)>0){
                    $School = $this->getDoctrine()->getRepository('FrontendAccountBundle:School')
                                ->getSchoolOrNullByName($school);
                    if($School === NULL){
                        $School = new \Frontend\AccountBundle\Entity\School();
                        $School->setCreatorUser($User);
                        $School->setCreatedAt(new \DateTime('now'));
                        $School->setName($school);
                        $School->setStatus(0);
                        $em->persist($School);
                    }
                    $UserSettings->setSchool($School);
                }
                
                $UserSettings->setName($name);
                $UserSettings->setGender($gender);
                $UserSettings->setBirthDate($bithDate);
                
                $em->persist($UserSettings);
                $em->flush();
                     
                $BaseSettingsForm = new BaseSettingsFormType();
                $BaseSettingsForm->setUser($User);
                $form = $this->createForm($BaseSettingsForm, null, array(
                    'action' => $url,
                    'method' => 'POST',
                    'attr' => array('class' => 'baseSettingsForm settings-form')
                ));
        
            }
        }
        
        $schools = $this->getDoctrine()->getRepository('FrontendAccountBundle:School')
                ->getAllActiveSchools();
        
        return $this->render('FrontendAccountBundle:Forms:baseSettingsForm.html.twig',array(
            'form' => $form->createView(),
            'schools' => $schools
        ));
    }
    
    public function safetySettingsAction(){
        $request = $this->get('request');
        $User = $this->get('security.context')->getToken()->getUser();
        
       
        $SafetySettingsForm = new SafetySettingsFormType();
        $SafetySettingsForm->setUserSettings($User->getUserSettings());
        
        $url = $this->generateUrl('safety_settings_save');
         
        $form = $this->createForm($SafetySettingsForm, null, array(
            'action' => $url,
            'method' => 'POST',
            'attr' => array('class' => 'safatySettingsForm settings-form')
        ));
        
        if($request->getMethod() === 'POST'){   
            $form->bind($request);
            if($form->isValid()){
                $formData = $form->getData();
                
                if(!$this->passwordControl($formData['password'], $User)){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Rossz a beírt jelszó, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                $myUploadsVisit = $formData['myUploadsVisit'];
                $myProfileVisit = $formData['myProfileVisit'];
                $messageToMe = $formData['messageToMe'];
                
                $UserSettings = $User->getUserSettings();
                if($UserSettings == NULL){
                    $UserSettings = new UserSettings();
                    $UserSettings->setUser($User);
                }
                $UserSettings->setMyUploadsVisit($myUploadsVisit);
                $UserSettings->setMessageToMe($messageToMe);
                $UserSettings->setMyProfileVisit($myProfileVisit);
                
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($UserSettings);
                $em->flush();
            }
        }
        
        return $this->render('FrontendAccountBundle:Forms:safetySettingsForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
    
    public function avatarSettingsAction(){
        $request = $this->get('request');
        $User = $this->get('security.context')->getToken()->getUser();
        
        $Avatars = array();
        
        $AvatarSettingsForm = new AvatarSettingsFormType();
        $AvatarSettingsForm->setUser($User);
        $AvatarSettingsForm->setUserSettings($User->getUserSettings());
        
        $url = $this->generateUrl('avatar_settings_save');
        
        $form = $this->createForm($AvatarSettingsForm, null, array(
            'action' => $url,
            'method' => 'POST',
            'attr' => array('class' => 'avatarSettingsForm settings-form')
        ));
        
        if($request->getMethod() === 'POST'){   
            $form->bind($request);
            if($form->isValid()){
                $formData = $form->getData();
                
                $avatarId = $formData['avatarId'];
                
                $Avatar = $this->getDoctrine()->getRepository('FrontendAccountBundle:Avatar')
                            ->findOneById($avatarId);
                
                if($Avatar === NULL){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Hibás az avatar azonosító, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                if($Avatar->getUser() !== $User){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'EZ AZ AVATÁR NEM A TE TULAJDONOD, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                if(!$this->passwordControl($formData['password'], $User)){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Rossz a beírt jelszó, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                $UserSettings = $User->getUserSettings();
                if($UserSettings == NULL){
                    $UserSettings->setUser($User);
                }
                
                $UserSettings->setAvatar($Avatar);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($UserSettings);
                $em->flush();
                
                // azért kell újra, mert akkor nem fogja az új képet megjeleníteni
                $AvatarSettingsForm = new AvatarSettingsFormType();
                $AvatarSettingsForm->setUser($User);
                $AvatarSettingsForm->setUserSettings($User->getUserSettings());
                
                $form = $this->createForm($AvatarSettingsForm, null, array(
                    'action' => $url,
                    'method' => 'POST',
                    'attr' => array('class' => 'avatarSettingsForm settings-form')
                ));
            }
        }
        
        $Avatars = $this->getDoctrine()->getRepository('FrontendAccountBundle:Avatar')
                    ->createQueryBuilder('avatar')
                    ->select('avatar')
                    ->addSelect('user')
                    ->addSelect('userSettings')
                    ->where('avatar.status = 1')
                    ->andWhere('user = :user')
                    ->join('avatar.user', 'user')
                    ->leftJoin('avatar.userSettings', 'userSettings')
                    ->orderBy('avatar.insertDate', 'DESC')
                    ->setParameter('user', $User)
                    ->getQuery()
                    ->getResult();
        $AvatarSettingsForm->setAvatar($User->getUserSettings()->getAvatar());
        
        return $this->render('FrontendAccountBundle:Forms:avatarSettingsForm.html.twig',array(
            'form' => $form->createView(),
            'Avatars' => $Avatars
        ));
    }
    
    public function commentAction(){
        $request = $this->get('request');
        $User = $this->get('security.context')->getToken()->getUser();
        
        $CommentSettingsForm = new CommentSettingsFormType();
        $CommentSettingsForm->setUserSettings($User->getUserSettings());
        
        $url = $this->generateUrl('comment_settings_save');
        
        $form = $this->createForm($CommentSettingsForm, null, array(
            'action' => $url,
            'method' => 'POST',
            'attr' => array('class' => 'commentSettingsForm settings-form')
        ));
        
        if($request->getMethod() === 'POST'){   
            $form->bind($request);
            if($form->isValid()){
                $formData = $form->getData();
               
                if(!$this->passwordControl($formData['password'], $User)){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Rossz a beírt jelszó, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                $commentText = $formData['commentText'];
                $UserSettings = $User->getUserSettings();
                if($UserSettings == NULL){
                    $UserSettings = new UserSettings();
                    $UserSettings->setUser($User);
                }
                $UserSettings->setCommentText($commentText);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($UserSettings);
                $em->flush();
            }
                 
        }
        
        return $this->render('FrontendAccountBundle:Forms:commentForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
    
    public function changePasswordAction(){
        $request = $this->get('request');
        $User = $this->get('security.context')->getToken()->getUser();
        
        $ChangePasswordForm = new ChangePasswordFormType();
        
        $url = $this->generateUrl('change_pass_save');
        
        $form = $this->createForm($ChangePasswordForm, null, array(
            'action' => $url,
            'method' => 'POST',
            'attr' => array('class' => 'changePassword settings-form')
        ));
        
        if($request->getMethod() === 'POST'){   
            $form->bind($request);
            if($form->isValid()){
                $formData = $form->getData();
               
                if(!$this->passwordControl($formData['password'], $User)){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'Rossz a beírt jelszó, az adatok NEM kerültek elmentésre!' 
                    ));
                }
                
                $pass = $formData['pass1'];
                
                if(strlen($pass)<6){
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                       'err' => 'A jelszó kevesebb mint 6 karakter' 
                    ));
                }
                $em = $this->getDoctrine()->getEntityManager();		

                $User->setPlainPassword($pass);
                $User->setConfirmationToken(NULL);
                $User->setPasswordRequestedAt(NULL);
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($User);
                
            }
                 
        }
        
        return $this->render('FrontendAccountBundle:Forms:changePasswordForm.html.twig',array(
            'form' => $form->createView()
        ));
    }
    
    private function passwordControl($pass, $User){
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($User);
        $encoded_pass = $encoder->encodePassword($pass, $User->getSalt());

        if($User->getPassword() !== $encoded_pass){
            return false;
        }else{
            return true;
        }
    }
    
    public function uploadAvatarAjaxAction(Request $request){
        try{
            $f = $request->files->get('image');
            
            $user = $this->get('security.context')->getToken()->getUser();
            
            if($f == NULL){
                throw new Exception('Null a file!');
            }
            
            $avatar = new Avatar();
            $avatar->setFile($f);
            $avatar->setUser($user);
            
            if(!$avatar->upload())
                throw new Exception('Hiba a feltöltés során!');
            
            $avatar->setInsertDate(new \DateTime('now'));
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($avatar);
            $em->flush();
            
            $avatarSRC = '/jegyzetbazis/web/' . $avatar->getWebPath();
            
            $avatar200 = $this->get('image.handling')->open($avatar->getWebPath())->zoomCrop(200,200)->jpeg();
            
            return new JsonResponse(array(
                'avatarSRC' => $avatarSRC,
                'avatar200SRC' => $avatar200,
                'avatarId' => $avatar->getId()
                ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
}
