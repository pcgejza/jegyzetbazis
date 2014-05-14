<?php

namespace Frontend\LayoutBundle\Controller;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TopController extends Controller{
   
   public function getTopFilesAction(){
       $User = $this->get('security.context')->getToken()->getUser();
       $User = is_object($User) ? $User : null;
       
       $Files = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
               ->createQueryBuilder('file')
               ->select('file')
               ->where('file.status = 1')
               ->andWhere(
                    "(userSettings.myUploadsVisit is null OR userSettings.myUploadsVisit = 'all') OR "
                    ."(userSettings.myUploadsVisit = 'only_users' and :myUser is not null) OR "
                    ."(userSettings.myUploadsVisit = 'only_friends' and (friendsA.status = 'active' or friendsB.status = 'active'))")
               ->join('file.user', 'user')
               ->leftJoin('user.userSettings', 'userSettings')
               ->leftJoin('user.friendsA', 'friendsA', 'WITH', 'friendsA.userB = :myUser')
               ->leftJoin('user.friendsB', 'friendsB', 'WITH', 'friendsB.userA = :myUser')
               ->setParameter('myUser', $User)
               ->orderBy('file.downloadCount', 'DESC')
               ->setMaxResults(5)
               ->getQuery()
               ->getResult();
       
       $Elements = array();
       foreach ($Files as $file) {
           $n = $file->getName();
           if(strlen($n)>15){
               $n = substr($n, 0, 11) . '...' . substr($n, strrpos($n,'.'));
           }
           
           $path = $this->generateUrl('file_single', array('fileid'=> $file->getId()));
           
           $Elements[] = array('name' => $n ,
               'path' => $path,
               'originalName' => $file->getName(),
               'value' => $file->getDownloadCount());
       }
       
       return $this->render('FrontendLayoutBundle:RightBoxes:box.html.twig',array(
           'Elements' => $Elements,
           'title' => 'Legnépszerűbb fájlok'
       ));
   }
    
    public function getTopUsersAction(){
       $User = $this->get('security.context')->getToken()->getUser();
       $User = is_object($User) ? $User : null;
       
       $Users = $this->getDoctrine()->getRepository('FrontendAccountBundle:User')
               ->createQueryBuilder('user')
               ->select('user.id AS userId')
               ->addSelect('userSettings.name AS userName')
               ->addSelect("(SELECT COUNT(f.id) FROM FrontendSubjectBundle:File f WHERE f.status = 1 and f.user = user) AS uploadCount")
               ->setMaxResults(5)
               ->join('user.userSettings', 'userSettings')
               ->orderBy('uploadCount', 'DESC')
               ->getQuery()
               ->getResult();
       
       
      /*         
               ->select('file')
               ->addSelect('file')
               ->where('file.status = 1')
               ->andWhere(
                    "(userSettings.myUploadsVisit is null OR userSettings.myUploadsVisit = 'all') OR "
                    ."(userSettings.myUploadsVisit = 'only_users' and :myUser is not null) OR "
                    ."(userSettings.myUploadsVisit = 'only_friends' and (friendsA.status = 'active' or friendsB.status = 'active'))")
               ->join('file.user', 'user')
               ->leftJoin('user.userSettings', 'userSettings')
               ->leftJoin('user.friendsA', 'friendsA', 'WITH', 'friendsA.userB = :myUser')
               ->leftJoin('user.friendsB', 'friendsB', 'WITH', 'friendsB.userA = :myUser')
               ->setParameter('myUser', $User)
       
       
       */
       $Elements = array();
       foreach ($Users as $u) {
           
           $n = $u['userName'];
           if(strlen($n)>15){
               $n = substr($n, 0, 11) . '...';
           }
           
           $path = $this->generateUrl('profile_show', array('id'=> $u['userId']));
           
           $Elements[] = array('name' => $n ,
               'path' => $path,
               'originalName' => $u['userName'],
               'value' => $u['uploadCount']);
       }
       
       return $this->render('FrontendLayoutBundle:RightBoxes:box.html.twig',array(
           'Elements' => $Elements,
           'title' => 'Top felhasználók'
       ));
    }
   
}
