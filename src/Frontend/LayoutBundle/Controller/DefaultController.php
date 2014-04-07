<?php
namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\LayoutBundle\Entity\Visitors;
use \Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendLayoutBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function visitAction()
    {
        $User = $this->container->get('security.context')->getToken()->getUser();
        $User = (is_object($User)) ? $User : NULL;
        $date = new \DateTime('now');
        $IP = $this->get('request')->getClientIp();
        
        if($this->get('request')->getMethod()!=='POST'){
            $Visitors = new Visitors();
            $Visitors->setUser($User);
            $Visitors->setIp($IP);
            $Visitors->setDate($date);
        }else{
            $IP = $this->get('request')->request->get('ip');
            $Visitors = $this->getDoctrine()->getRepository('FrontendLayoutBundle:Visitors')
                        ->createQueryBuilder('v')
                        ->where('v.ip = :ip')
                        ->setParameter('ip', $IP)
                        ->setMaxResults(1)
                        ->orderBy('v.date', 'DESC')
                        ->getQuery()
                        ->getSingleResult();
            $Visitors->setDate($date);
            
            $d = $date;
            $count = $this->getDoctrine()->getRepository('FrontendLayoutBundle:Visitors')
                    ->createQueryBuilder('v')
                    ->select('COUNT(DISTINCT v.ip)')
                    ->where('v.date > :d')
                    ->setParameter('d', $d->modify("-1 minutes"), \Doctrine\DBAL\Types\Type::DATETIME)
                    ->getQuery()
                    ->getSingleScalarResult();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($Visitors);
        $em->flush();
        
        if($this->get('request')->getMethod()==='POST'){ return new JsonResponse(array('count'=>$count)); }
        
        return new \Symfony\Component\HttpFoundation\Response($IP);
    }
}
