<?php

namespace Frontend\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Frontend\MessagingBundle\Entity\Message;

class DefaultController extends Controller{
    
    /*
     * Az üzenetek oldal renderelése
     */
    public function indexAction($page = 'beerkezett-uzenetek', $messageId = null)
    {
        try{
            if(!$this->get('security.context')->isGranted('ROLE_USER')){
                // ha nincs belépve vissza irányítom a főoldalra
                return $this->redirect($this->generateUrl('frontend_index_homepage'));
            }

            if($this->get('request')->isMethod('POST')){
                $page = $this->get('request')->request->get('page');
                $messageId = $this->get('request')->request->get('messageId');
                return $this->getPageAction($page, $messageId);
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
                case 'uzenet':
                    $getP = 'single';
                    break;
                default : throw new Exception('Nincs ilyen oldal!');
            }
                

            return $this->render('FrontendMessagingBundle:Default:index.html.twig', array(
                'getPage' => $getP,
                'messageId' => $messageId
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba : '.$ex->getMessage());
        }
    }
    
    
    public function contentAction($getPage, $messageId = null){
        return $this->render('FrontendMessagingBundle:Default:content.html.twig', array(
            'page' => $getPage,
            'messageId' => $messageId
        ));
    }
    
    /*
     * Oldal lekérdezése
     */
    public function getPageAction($page = null, $messageId = null){
        try{
            return $this->render('FrontendMessagingBundle:Default:page.html.twig', array(
                'page' => $page,
                'messageId' => $messageId
            ));
        } catch (Exception $ex) {
            return $this->render('FrontendMessagingBundle:Default:page.html.twig', array(
                'err' => $ex->getMessage()
            ));
        }
    }
    
    /*
     * Egy üzenet(üzenetváltás) lekérdezése
     */
    public function getSingleMessageAction($messageId = null){
        $request = $this->get('request');
        try{
            $User = $this->get('security.context')->getToken()->getUser();
            
            if(!is_object($User)){
                throw new Exception('Nem vagy bejelentkezve, ehhez a funkcióhoz nincs jogosultságod!');
            }
            
            if($request->isMethod('POST')){
                $messageId = $request->request->get('messageId');
            }
            
            if($messageId == NULL){
                throw new Exception('Hibás messageId');
            }
            
            $Message = $this->getDoctrine()->getRepository('FrontendMessagingBundle:Message')
                        ->getOneById($messageId);
            
            if($Message === NULL || sizeof($Message)==0){
                throw new Exception('Nincs ilyen üzenet!');
            }
            
            
            if($Message[0]->getUserA() != $User && $Message[0]->getUserB() != $User){
                 throw new Exception('Nincs jogosultságod az üzenet megtekintéséhez mert nem te küldted és nem is te vagy a címzett!');
            }
            
            $m0 = null;
            foreach($Message as $me){
                if($me->getId() == $messageId && $me->getUserB() == $User){
                    $m0 = $me;
                }
            }
            
            //olvasottnak jelölni
            if($m0 != NULL){
                $m0->setSeeTime(new \DateTime('now'));
                $em = $this->getDoctrine()->getManager();
                $em->persist($m0);
                $em->flush();
            }
            
            return $this->render('FrontendMessagingBundle:Default:single.html.twig', array(
                'Message' => $Message,
                'msgId' => $messageId
            ));
        } catch (Exception $ex) {
            if($request->isMethod('POST')){
                return new JsonResponse(array('err' => $ex->getMessage()));
            }else{
                return $this->render('FrontendMessagingBundle:Default:single.html.twig', array(
                    'err' =>$ex->getMessage()
                ));
            }
        }
    }
    
    /*
     * üzenet olvasása -> ebben az esetben az üzenetet olvasottnak jelölni
     *  ha a megtekintő nem azonos a küldővel
     */
    public function seeMessageAction(){
        $request = $this->get('request');
        if(!$request->isMethod('POST')){
            throw new \ErrorException('Ezt az oldalt nem így kell megnyitni!');
        }
        try{
            $see_d = false;
            $msgid = $request->request->get('msgid');
            if($msgid == NULL || strlen($msgid)==0){
                throw new Exception('Hibás az msgid!');
            }
            
            $Message = $this->getDoctrine()->getRepository('FrontendMessagingBundle:Message')
                        ->findOneById($msgid);
            
            if($Message == NULL){
                throw new Exception('nincs ilyen azonosítóju üzenet');
            }

            if($Message->getSeeTime() === NULL){
                $User = $this->get('security.context')->getToken()->getUser();

                if(!is_object($User)){
                    throw new Exception('Nincs bejelentkezve felhasználó!');
                }

                if( !($Message->getUserA() == $User || $Message->getUserB() == $User)){
                    throw new Exception('Ez az üzenet nem a te tulajdonod!');
                }

                if($Message->getUserB() == $User){ // csak akkor szabad olvasottnak jelölni ha én vagyok a címzett
                    $Message->setSeeTime(new \DateTime('now'));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($Message);
                    $em->flush();
                    $see_d = true;
                }
            }
            return new JsonResponse(array(
                'see_d' => $see_d
            ));
        } catch (Exception $ex) {
            return new JsonResponse(array(
                'err' => $ex->getMessage()
            ));
        }
    }
    
    /*
     * További üzenetek lekérdezése (FIXME: még fejleszteni kell rajta)
     */
    public function getMoreMessagesAction($page = null){
        $request = $this->get('request');
        try{
            $User = $this->get('security.context')->getToken()->getUser();
            
            if($request->isMethod('POST')){
                
            }else{
                $Messages = $this->getDoctrine()->getRepository('FrontendMessagingBundle:Message')
                            ->getMessagesByUser($User, 0, 1000, $page);   
                
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
    
    /*
     * Üzenet küldése
     */
    public function sendMessageAction(){
        try{
            $request = $this->get('request');
            $em = $this->getDoctrine()->getManager();
            
            if(!$request->isMethod('POST')){ // ha nem a form küldéséből érkező kérés
                throw new \ErrorException('Nem POST a metódus így nincs üzenet továbbítás!');
            }
            
            $nickOrEmail = $request->request->get('nick');
            $message = $request->request->get('message');
            $parentId = $request->request->get('parentid');
            
            $Parent = NULL; 
           
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
             
            if($parentId != NULL && $parentId != '0' && $parentId != 0){
                $Parent = $em->getReference('FrontendMessagingBundle:Message', $parentId);
                if( ! (($Parent->getUserA() == $User && $Parent->getUserB() == $ToUser) || ($Parent->getUserA() == $ToUser && $Parent->getUserB() == $User)) ){
                    throw new Exception('Nem küldhetsz így üzenetet, hiszen nem te vagy a tulajdonsosa ennek a szülő mail-nek');
                }
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
            
            $em->persist($Message);
            $em->flush();
            
            $messageDIV = $this->renderView('FrontendMessagingBundle:Default:singleDiv.html.twig', array(
               'cls' => true,
                'Message' => $Message
            ));
            
            return new JsonResponse(array('messageDIV' => $messageDIV));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    /*
     * Üzenet törlése
     */
    public function deleteMessageAction(){
        $request = $this->get('request');
        if(!$request->isMethod('POST')){
            throw new \ErrorException('Ez az üzenet törlés funkció, nem pedig egy weblap!');
        }
        try{
            $messageId = $request->request->get('messageId');
            $em = $this->getDoctrine()->getManager();
            
            if($messageId == NULL)
                throw new Exception('A messageId null!');
            
            $Message = $this->getDoctrine()->getRepository('FrontendMessagingBundle:Message')
                        ->createQueryBuilder('m')
                        ->select('m')
                        ->addSelect('userA')
                        ->addSelect('userB')
                        ->join('m.userA', 'userA')
                        ->join('m.userB', 'userB')
                        ->where('m.id = :id')
                        ->setParameter('id', $messageId)
                        ->getQuery()
                        ->getOneOrNullResult();
            
            if($Message == NULL){
                  throw new Exception('Nem létezik ilyen azonosítóju üzenet : '.$messageId.'!');
            }
            
            $User = $this->get('security.context')->getToken()->getUser();
            
            if(!is_object($User)){
                throw new Exception('Nincs belépett felhasználó!');
            }
            
            if($Message->getUserA() == $User){
                $Message->setStatus('deleted_by_a');
            }elseif($Message->getUserB() == $User){
                $Message->setStatus('deleted_by_b');
            }else{
                throw new Exception('Nem a te tulajdonod ez az üzenet!');
            }
            
            $em->persist($Message);
            $em->flush();
            
            return new JsonResponse(array());
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
}
