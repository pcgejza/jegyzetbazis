<?php

namespace Frontend\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Frontend\MessagingBundle\Entity\Message;

class DefaultController extends Controller
{
    public function indexAction($page = 'beerkezett-uzenetek')
    {
        try{
            if(!$this->get('security.context')->isGranted('ROLE_USER')){
                // ha nincs belépve vissza irányítom a főoldalra
                return $this->redirect($this->generateUrl('frontend_index_homepage'));
            }

            if($this->get('request')->isMethod('POST')){
                $page = $this->get('request')->request->get('page');
                return $this->getPageAction($page);
            }
            
            
            $getP = null;
            switch($page){
                case 'beerkezett-uzenetek':
                    $getP = 'received';
                    break;
                case 'elkuldott-uzenetek':
                    $getP = 'sent';
                    break;
                case 'torolt-uzenetek':
                    $getP = 'deleted';
                    break;
                default : throw new Exception('Nincs ilyen oldal!');
            }
            
                

            return $this->render('FrontendMessagingBundle:Default:index.html.twig', array(
                'getPage' => $getP
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba : '.$ex->getMessage());
        }
    }
    
    
    public function contentAction($getPage){
        
        return $this->render('FrontendMessagingBundle:Default:content.html.twig', array(
            'page' => $getPage
        ));
    }
    
    public function getPageAction($page = null){
        try{
            return $this->render('FrontendMessagingBundle:Default:page.html.twig', array(
                'page' => $page
            ));
        } catch (Exception $ex) {
            return $this->render('FrontendMessagingBundle:Default:page.html.twig', array(
                'err' => $ex->getMessage()
            ));
        }
    }
    
    public function getMoreMessagesAction($page = null){
        $request = $this->get('request');
        try{
            $User = $this->get('security.context')->getToken()->getUser();
            
            if($request->isMethod('POST')){
                
            }else{
                
                
                $Messages = $this->getDoctrine()->getRepository('FrontendMessagingBundle:Message')
                            ->getMessagesByUser($User, 0, 10, $page);   
                
                return $this->render('FrontendMessagingBundle:Default:more.html.twig', array(
                    'Messages' => $Messages,
                    'page' => $page
                ));
            }
        } catch (Exception $ex) {
            if($request->isMethod('POST')){
                return new JsonReponse(array('err' => 'Hiba : '.$ex->getMessage()));
            }else{
                return new Response('Hiba : '.$ex->getMessage());
            }
        }
    }
    
    
    public function sendMessageAction(){
        try{
            $request = $this->get('request');
            
            if(!$request->isMethod('POST')){ // ha nem a form küldéséből érkező kérés
                throw new \ErrorException('Nem POST a metódus így nincs üzenet továbbítás!');
            }
            
            $nickOrEmail = $request->request->get('nick');
            $message = $request->request->get('message');
            $parentId = $request->request->get('parent');
            
            $Parent = NULL; // KÉSŐBB KIDOLGOZANDÓ
            
            if($nickOrEmail == NULL || strlen($nickOrEmail)==0)
                throw new Exception('Hibás nick név vagy email cím!');
            
            if($message == NULL || strlen($message)==0)
                throw new Exception('Hibás üzenet!');
            
            $User = $this->get('security.context')->getToken()->getUser();
            
            if(!is_object($User)){
                throw new Exception('Nincs belépve felhasználó!');
            }
            
            $ToUser = $this->getDoctrine()->getRepository('FrontendAccountBundle:User')
                        ->createQueryBuilder('u')
                        ->select('u, uS')
                        ->where('u.username = :n OR u.email = :n')
                        ->join('u.userSettings', 'uS')
                        ->setParameter('n', $nickOrEmail)
                        ->getQuery()
                        ->getOneOrNullResult();
            
            if($ToUser === NULL){
                 throw new Exception('Nem létezik ilyen felhasználó!');
            }
            
            if($ToUser->getUserSettings()->getMessageToMe()=='only_friends'){
                $isFriends = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                           ->isFriends($User, $ToUser);
                if(!$isFriends)
                    throw new Exception('Ennek a felhasználónak csak a barátai küldhetnek üzenetet!');
            }
            
            $Message = new Message();
            $Message->setUserA($User);
            $Message->setUserB($ToUser);
            $Message   ->setText($message)
                    ->setParent($Parent);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($Message);
            $em->flush();
            
            return new JsonResponse();
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
}
