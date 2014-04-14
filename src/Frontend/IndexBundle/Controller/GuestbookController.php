<?php

namespace Frontend\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

use Frontend\IndexBundle\Entity\GuestBook;

class GuestbookController extends Controller{
   
    public function guestBookAction(){
        return $this->render('FrontendIndexBundle:Guestbook:index.html.twig');
    }
    
    public function entriesAction(){
        $bookings = $this->getDoctrine()->getRepository('FrontendIndexBundle:GuestBook')
                    ->createQueryBuilder('booking')
                    ->select('booking, user, userSettings')
                    ->join('booking.user', 'user')
                    ->leftJoin('user.userSettings', 'userSettings')
                    ->orderBy('booking.insertDate', 'DESC')
                    ->getQuery()
                    ->getResult();
        
         return $this->render('FrontendIndexBundle:Guestbook:entries.html.twig', array(
             'bookings' => $bookings
         ));
    }
    
    public function newBookingAction(\Symfony\Component\HttpFoundation\Request $request){
        try{
            $text = $request->request->get('text');
            $User = $this->get('security.context')->getToken()->getUser();
            
            if($text == null || strlen($text)<1)
                throw new Exception('Hiba a text változóval!');
            
            if(!is_object($User))
                throw new Exception('Nem belépett User!');
            
            $GuestBook = new GuestBook();
            $GuestBook->setUser($User);
            $GuestBook->setText($text);
            $GuestBook->setInsertDate(new \DateTime('now'));
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($GuestBook);
            $em->flush();
            
            $newRow = $this->renderView('FrontendIndexBundle:Guestbook:oneRow.html.twig', array(
                'name' => $GuestBook->getUser(),
                'date' => $GuestBook->getInsertDate(),
                'text' => $GuestBook->getText()
            ));
            
            return new JsonResponse(array(
                'newRow' => $newRow
            ));
        } catch (Exception $ex) {
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
}
