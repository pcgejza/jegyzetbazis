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
    
}
