<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
                'FriendsObject' => $FriendsObject
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba a getFriendsButtonsAction-ben : '+$ex->getMessage());
        }
    }
}
