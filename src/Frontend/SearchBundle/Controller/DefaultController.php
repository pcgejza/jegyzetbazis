<?php

namespace Frontend\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
   public function mainSearchAutocompleteAction()
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            
            $key = $request->request->get('searchText');
        
            $doctrine = $this->getDoctrine(); 
            $queryBuilder = $doctrine->getEntityManager()->createQueryBuilder();
            
            $User = $this->get('security.context')->getToken()->getUser();
            $User = is_object($User) ? $User : null;
            
             // Tantárgyak
            $Subjects = $doctrine->getRepository('FrontendSubjectBundle:Subject')
                            ->createQueryBuilder('Subject')
                            ->select('Subject.name AS name')
                            ->addSelect('Subject.slug AS slug')
                            ->where($queryBuilder->expr()->like('LOWER(Subject.name)', 'LOWER(:S)')) 
                            ->andWhere('Subject.status = 1')
                            ->setParameter('S', '%'.$key.'%')
                            ->setMaxResults(5)
                            ->getQuery()
                            ->getResult();
            
            // Fájlok
            $Files = $doctrine->getRepository('FrontendSubjectBundle:File')
                            ->createQueryBuilder('File')
                            ->select('File.name AS name')
                            ->addSelect('File.id AS id')
                            ->where($queryBuilder->expr()->like('LOWER(File.name)', 'LOWER(:S)')) 
                            ->andWhere(
                                    ":MyUser = user OR ".
                                    "(uS.myUploadsVisit is null or uS.myUploadsVisit = 'all') OR ".
                                    "(uS.myUploadsVisit = 'only_users' and :MyUser is not null) OR ".
                                    "(uS.myUploadsVisit = 'only_friends' and (friendlyA.status = 'active' or friendlyB.status = 'active'))"
                                    )
                            ->join('File.user', 'user')
                            ->join('user.userSettings', 'uS')
                            ->leftJoin('user.friendsA', 'friendlyA', 'WITH', 'friendlyA.userB = :MyUser')
                            ->leftJoin('user.friendsB', 'friendlyB', 'WITH', 'friendlyB.userA = :MyUser')
                            ->setParameter('MyUser', $User)
                            ->setParameter('S', '%'.$key.'%')
                            ->setMaxResults(5)
                            ->getQuery()
                            ->getResult();
            
             // EMBEREK
            $UserSettings = $doctrine->getRepository('FrontendAccountBundle:UserSettings')
                            ->createQueryBuilder('UserSettings')
                            ->select('UserSettings.name AS name')
                            ->addSelect('UserSettings.userId AS userID')
                            ->where($queryBuilder->expr()->like('LOWER(UserSettings.name)', 'LOWER(:S)')) 
                            //->andWhere("user != :User") // saját magamra ne lehessen rákeresni...
                            ->andWhere(""
                         ."(UserSettings.myProfileVisit = 'only_friends' and (userFriendsA.status = 'active' OR userFriendsB.status = 'active'))"
                         ."OR (UserSettings.myProfileVisit = 'only_users' and :User is not null)"
                         . " OR (UserSettings.myProfileVisit = 'all' OR UserSettings.myProfileVisit is null)")
                            ->join('UserSettings.user', 'user')
                            ->leftJoin('user.friendsA', 'userFriendsA', 'userFriendsA.userB = :User')
                            ->leftJoin('user.friendsB', 'userFriendsB', 'userFriendsB.userB = :User')
                            ->setParameter('S', '%'.$key.'%')
                            ->setParameter('User', $User)
                            ->orderBy('userFriendsA.id', 'ASC')
                            ->addOrderBy('userFriendsB.id', 'ASC')
                            ->groupBy('UserSettings')
                            ->setMaxResults(5)
                            ->getQuery()
                            ->getResult();
            
            
            $returnArray = array();
            
            foreach($Subjects as $subject){
                $returnArray[] = array(
                    'name' => $subject['name'],
                    'link' => $this->generateUrl('subjects_homepage_as_one_subject', array('subject' => $subject['slug'])),
                    'category' => 'Tantárgyak'
                );
             }
             
            foreach($Files as $file){
                $returnArray[] = array(
                    'name' => $file['name'],
                    'link' => $this->generateUrl('file_single', array('fileid' => $file['id'])),
                    'category' => 'Fájlok'
                );
             }
             
            foreach($UserSettings as $us){
                $returnArray[] = array(
                    'name' => $us['name'],
                    'link' => $this->generateUrl('profile_show', array('id' => $us['userID'])),
                    'category' => 'Emberek'
                );
             }
            
            return new JsonResponse($returnArray);
        }else{
            return new JsonResponse(array('success' => false));
        }
    }   
}
