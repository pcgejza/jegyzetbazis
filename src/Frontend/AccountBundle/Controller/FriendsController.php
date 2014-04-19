<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Frontend\AccountBundle\Entity\Friends;

class FriendsController extends Controller{
    
    public function indexAction(){
        if(!$this->get('security.context')->isGranted('ROLE_USER')){
            return $this->redirect($this->generateUrl('frontend_index_homepage'));
        }
        
        return $this->render('FrontendAccountBundle:Friends:index.html.twig');
    }
    
    public function getFriendsAction(){
        try{
            $user = $this->get('security.context')->getToken()->getUser();
            
            $Friends = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                        ->getUserFriendsByUser($user);
            
            return $this->render('FrontendAccountBundle:Friends:friends.html.twig',array(
                'Friends' => $Friends
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba a getFriends-ben : '+$ex->getMessage());
        }
    }
    
    public function getFriendsButtonsAction($viewedUser){
        try{
            $MyUser = $this->get('security.context')->getToken()->getUser();
            
            $FriendsObject = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                        ->getFriendsStatus($MyUser, $viewedUser);
            
            return $this->render('FrontendAccountBundle:Friends:globalFriendsButtons.html.twig',array(
                'FriendsObject' => $FriendsObject,
                'ViewedUser' => $viewedUser
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba a getFriendsButtonsAction-ben : '+$ex->getMessage());
        }
    }
    
    public function friendsAddOrRemoveAction(){
        try{
            $MyUser = $this->get('security.context')->getToken()->getUser();
            $viewedUserId = $this->get('request')->request->get('userID');
            
            if(!is_object($MyUser))
                throw new Exception('Nincs bejelentkezve!');
            
            if(!is_numeric($viewedUserId))
                throw new Exception('Nincs megadva a userID!');
            
            $em = $this->getDoctrine()->getEntityManager();
            $viewedUser = $em->getReference('FrontendLayoutBundle:User', $viewedUserId);
                
            $FriendsObject = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                        ->getFriendsStatus($MyUser, $viewedUser);
            
            $text = "";
            
            if($FriendsObject == NULL){
                $FriendsObject = new Friends();
                $FriendsObject->setUserA($MyUser);
                $FriendsObject->setUserB($viewedUser);
                $FriendsObject->setMarkDate(new \DateTime('now'));
                $text = 'Jelölés törlése';
            }else{
                if($FriendsObject->getUserA() == $MyUser){
                    if($FriendsObject->getStatus() == 'selected'){
                        $text = 'Barátnak jelölés';
                        $FriendsObject->setStatus('selected-rejected');
                    }elseif($FriendsObject->getStatus() == 'rejected' || $FriendsObject->getStatus() == 'selected-rejected'){
                        $text = 'Jelölés törlése';
                        $FriendsObject->setStatus('selected');
                    }elseif($FriendsObject->getStatus() == 'active'){
                        $text = 'Barátnak jelölés';
                        $FriendsObject->setStatus('selected-rejected');
                    }
                }else{
                    if($FriendsObject->getStatus() == 'selected'){
                        $text = 'Barátnak jelölés';
                        $FriendsObject->setStatus('selected-rejected');
                    }elseif($FriendsObject->getStatus() == 'rejected' || $FriendsObject->getStatus() == 'selected-rejected'){
                        $text = 'Jelölés törlése';
                        $FriendsObject->setStatus('selected');
                    }elseif($FriendsObject->getStatus() == 'active'){
                        $text = 'Barátnak jelölés';
                        $FriendsObject->setStatus('selected-rejected');
                    }
                }
            }
            $em->persist($FriendsObject);
            $em->flush();
            
            
            return new JsonResponse(array(
                'text' => $text
            ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err'=>$ex->getMessage()));
        }
    }
}
