<?php

namespace Frontend\LayoutBundle\Controller;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\Security\Core\Exception;
use Frontend\LayoutBundle\Controller\Logger;

class SearchController extends Controller{
    
    public function searchAction(){
        try{
            $text = $this->get('request')->request->get('text');
            
            $sessionID = $this->get('request')->getSession()->get('sessionID');
            
            $doctrine = $this->getDoctrine();
            $queryBuilder = $doctrine->getEntityManager()->createQueryBuilder();
            
            $User = $this->get('security.context')->getToken()->getUser();
            $User = is_object($User) ? $User : NULL;
           
            //LOG : FIXME: nem tudom elérni az entity managert!!
            /*
            $Logger = new Logger($this->getDoctrine()->getEntityManager());
            $Logger ->setUser($User)
                    ->setSessionID($sessionID)
                    ->setType('search')
                    ->setValue($text)
                    ->exec();
            */
            // EMBEREK
            $UserSettings = $doctrine->getRepository('FrontendAccountBundle:UserSettings')
                            ->createQueryBuilder('UserSettings')
                            ->select('UserSettings.name AS name')
                            ->addSelect('UserSettings.userId AS userID')
                            ->where($queryBuilder->expr()->like('LOWER(UserSettings.name)', 'LOWER(:S)')) 
                            ->andWhere(""
                         ."(UserSettings.myProfileVisit = 'only_friends' and (userFriendsA.status = 'active' OR userFriendsB.status = 'active'))"
                         ."OR (UserSettings.myProfileVisit = 'only_users' and :User is not null)"
                         . " OR (UserSettings.myProfileVisit = 'all' OR UserSettings.myProfileVisit is null)")
                            ->join('UserSettings.user', 'user')
                            ->leftJoin('user.friendsA', 'userFriendsA', 'userFriendsA.userB = :User')
                            ->leftJoin('user.friendsB', 'userFriendsB', 'userFriendsB.userB = :User')
                            ->setParameter('S', '%'.$text.'%')
                            ->setParameter('User', $User)
                            ->orderBy('userFriendsA.id', 'ASC')
                            ->setMaxResults(5)
                            ->getQuery()
                            ->getResult();
            
            // Tantárgyak
            $Subjects = $doctrine->getRepository('FrontendSubjectBundle:Subject')
                            ->createQueryBuilder('Subject')
                            ->select('Subject.name AS name')
                            ->addSelect('Subject.id AS id')
                            ->where($queryBuilder->expr()->like('LOWER(Subject.name)', 'LOWER(:S)')) 
                            ->setParameter('S', '%'.$text.'%')
                            ->getQuery()
                            ->getResult();
            
            // Fájlok
            $Files = $doctrine->getRepository('FrontendSubjectBundle:File')
                            ->createQueryBuilder('File')
                            ->select('File.name AS name')
                            ->addSelect('File.id AS userID')
                            ->where($queryBuilder->expr()->like('LOWER(File.name)', 'LOWER(:S)')) 
                            ->setParameter('S', '%'.$text.'%')
                            ->getQuery()
                            ->getResult();
            
            $Results['uSettings'] = $UserSettings;
            $Results['subjects'] = $Subjects;
            $Results['files'] = $Files;
            
            /*
            $qb = $em->createQueryBuilder;

            $qb->select(array('a', 'c'))
               ->from('Sdz\BlogBundle\Entity\Article', 'a')
               ->leftJoin('a.comments', 'c');

            $query = $qb->getQuery();
            $results = $query->getResult();

          
            $Results = $qb
                    ->select('qb AS uS')
                    ->addSelect('sub AS subject')
                    ->where($qb->expr()->like('qb.name', ':S')) //EMBEREK
                //    ->orWhere('')//tantárgyak
                  //  ->orWhere('')//fájlok
                    ->leftJoin('FrontendSubjectBundle:Subject', 'sub', 'WITH', 'sub.status = 1')
                    ->leftJoin('FrontendSubjectBundle:File', 'file', 'WITH', 'file.status = 1')
                    ->setParameter('S', '%'.$text.'%')
                    ->getQuery()
                    ->getResult();
              */
            
            
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
               'results' => array(
                   array('name' => 'géza', 'val' => 'timi'),
                   array('name' => 'géza2', 'val' => 'timi2'),
                   array('name' => 'géza3', 'val' => 'timi3'),
                   array('name' => 'géza4', 'val' => 'timi4'),
               ) 
            ));
            
            return $this->render('FrontendLayoutBundle:Search:searchResults.html.twig',
                    array(
                        'text' => $text,
                        'Results' => $Results
                    ));
        } catch (Exception $ex) {
            return $this->render('FrontendLayoutBundle:Search:searchResults.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
}
