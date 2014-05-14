<?php

namespace Frontend\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;

class ShowController extends Controller {
    
    public function indexAction($id){
            $User = $this->getDoctrine()->getRepository('FrontendAccountBundle:User')
                        ->findOneUserById($id);
        try{
            
            if(!$User)
                throw new Exception('Nincs ilyen felhasználó!', -1);
            
            $MyUser = $this->get('security.context')->getToken()->getUser();
            $MyUser = is_object($MyUser) ? $MyUser : null;
            
            if($User == $MyUser){
                $url = $this->generateUrl('settings_sub_page' ,array('page'=>'alap-beallitasok'));
                return $this->redirect($url);
            }
            
            if(!is_object($MyUser) && ($User->getUserSettings() && ($User->getUserSettings()->getMyProfileVisit() == 'only_users')))
                throw new Exception('A felhasználó beállításai szerint csak bejelentkezett felhasználó tekintheti meg az adatait', -1);
            
            $FriendsObject = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                        ->getFriendsStatus($MyUser, $User);
            
            if(($User->getUserSettings() && ($User->getUserSettings()->getMyProfileVisit() == 'only_friends'))
               && ($FriendsObject == NULL || $FriendsObject->getStatus() != 'active')     
                    )
                throw new Exception('A felhasználó beállításai szerint csak a barátai tekinthetik meg az adatait', -1);
            
            $UploadedFilesCount = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
                                ->getFilesCountByUser($User);
            
            $lastActive = $this->getDoctrine()->getRepository('FrontendLayoutBundle:Visitors')
                            ->createQueryBuilder('v')
                            ->where('user = :User')
                            ->join('v.user', 'user')
                            ->setParameter('User', $User)
                            ->setMaxResults(1)
                            ->orderBy('v.date', 'DESC')
                            ->getQuery()
                            ->getOneOrNullResult();
            
            $friendsCount = $this->getDoctrine()->getRepository('FrontendAccountBundle:Friends')
                        ->getAllActiveFriendsCount($User);
            
            
            return $this->render('FrontendAccountBundle:Show:index.html.twig',array(
                'User' => $User,
                'MyUser' => $MyUser,
                'lastActive' => $lastActive,
                'UploadedFilesCount' => $UploadedFilesCount,
                'friendsCount' => $friendsCount,
                'FriendsObject' => $FriendsObject
            ));
        }catch(Exception $e){
             return $this->render('FrontendAccountBundle:Show:index.html.twig',array(
                'err' => $e->getMessage(),
                'errCode' => $e->getCode(),
                'User' => $User,
                'MyUser' => isset($MyUser) ? $MyUser : null
            ));
        }
    }
     
    public function getUploadFilesAction($User){
        try{
            $Files = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
                        ->getFilesByUser($User);
            
            return $this->render('FrontendAccountBundle:Show:userUploads.html.twig',array(
                'Files' => $Files
            ));
        } catch (Exception $ex) {
            throw new \ErrorException('Hiba a getUploadFilesController-ben: '.$ex->getMessage());
        }
    }
    
    
}
