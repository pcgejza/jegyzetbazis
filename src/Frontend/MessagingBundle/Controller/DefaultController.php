<?php

namespace Frontend\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            
            if($request->isMethod('POST')){
                
            }else{
                return $this->render('FrontendMessagingBundle:Default:more.html.twig');
            }
        } catch (Exception $ex) {
            if($request->isMethod('POST')){
                return new JsonReponse(array('err' => 'Hiba : '.$ex->getMessage()));
            }else{
                return new Response('Hiba : '.$ex->getMessage());
            }
        }
    }
    
}
