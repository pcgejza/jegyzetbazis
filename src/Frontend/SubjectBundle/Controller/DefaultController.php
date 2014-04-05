<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

class DefaultController extends Controller
{
    public function indexAction($subject = NULL)
    {
        return $this->render('FrontendSubjectBundle:Default:index.html.twig',
                array('subject'=>$subject));
    }
    
    public function getAllSubjectsAction($subject = null){
        try{
            
            return $this->render('FrontendSubjectBundle:Default:allSubjects.html.twig',
                    array(
                        'subject' => $subject
                    ));
        } catch (Exception $ex) {
            return $this->render('FrontendSubjectBundle:Default:allSubjects.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
    
    public function getSubjectAction($subject = null){
        try{
            
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array('subject'=>$subject));
        } catch (Exception $ex) {
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
}
