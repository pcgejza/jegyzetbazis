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

            if($User == $MyUser){
                $url = $this->generateUrl('settings_sub_page' ,array('page'=>'alap-beallitasok'));
                return $this->redirect($url);
            }
            
            
            if(!is_object($MyUser) && ($User->getUserSettings() && ($User->getUserSettings()->getMyProfileVisit() == NULL || $User->getUserSettings()->getMyProfileVisit() != 'all')))
                throw new Exception('A felhasználó beállításai szerint csak bejelentkezett felhasználó tekintheti meg az adatait', -1);
            
            /* Barátok vizsgálása TODO: később lefejleszteni
            if(is_object($MyUser) && ($User->getUserSettings() && $User->getUserSettings()->getMyProfileVisit()=='only_friends'))
                throw new Exception('A felhasználó beállításai szerint csak barátok tekinthetik meg az adatait', -2);
            */
            
            $UploadedFilesCount = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
                                ->getFilesCountByUser($User);
            
            return $this->render('FrontendAccountBundle:Show:index.html.twig',array(
                'User' => $User,
                'MyUser' => $MyUser,
                'UploadedFilesCount' => $UploadedFilesCount
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
    
}
