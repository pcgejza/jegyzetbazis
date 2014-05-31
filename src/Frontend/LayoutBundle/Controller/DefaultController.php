<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\LayoutBundle\Entity\Visitors;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/*
 * az alap oldal kerethez szükséges függvényekhez írt controller
 */
class DefaultController extends Controller{
    
    // látogatás függvény
    public function visitAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->get('sessionID')){
            //random string generálás
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < 20; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            //sessionID beállítás
            $session->set('sessionID',$randomString);
            $Visitors = new Visitors($randomString);
        }else{
            $Visitors = $this->getDoctrine()->getRepository('FrontendLayoutBundle:Visitors')
                        ->findOneBySessionId($session->get('sessionID'));
        }
        
        $User = $this->container->get('security.context')->getToken()->getUser();
        $User = (is_object($User)) ? $User : NULL;
        $date = new \DateTime('now');
        
        $Visitors->setDate($date);
        $Visitors->setUser($User);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($Visitors);
        $em->flush();
        
        $d = $date;
        $count = $this->getDoctrine()->getRepository('FrontendLayoutBundle:Visitors')
                ->createQueryBuilder('v')
                ->select('COUNT(DISTINCT v.sessionId)')
                ->where('v.date > :d')
                ->setParameter('d', $d->modify("-30 seconds"), \Doctrine\DBAL\Types\Type::DATETIME)
                ->getQuery()
                ->getSingleScalarResult();
        
        return new \Symfony\Component\HttpFoundation\Response($count);
    }
    
    // az összes letöltés lekérdezéséhez szükséges függvény
    public function getAllDownloadsCountAction(){
       $count = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
               ->createQueryBuilder('file')
               ->select('SUM(file.downloadCount)')
               ->where('file.status = 1')
               ->getQuery()
               ->getSingleScalarResult();
       return new \Symfony\Component\HttpFoundation\Response($count);
    }
}
