<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

class DefaultController extends Controller
{
    public function indexAction($subject = NULL)
    {
        if($this->get('request')->getMethod() === 'POST'){
            return $this->getSubjectAction($subject);
        }
        
        return $this->render('FrontendSubjectBundle:Default:index.html.twig',
                array('subject'=>$subject));
    }
    
    public function getAllSubjectsAction($subject = null){
        try{
            $Subjects = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                            ->getAllSubjects();
                    
            return $this->render('FrontendSubjectBundle:Default:allSubjects.html.twig',
                    array(
                        'Subjects' => $Subjects,
                        'subject' => $subject
                    ));
        } catch (Exception $ex) {
            return $this->render('FrontendSubjectBundle:Default:allSubjects.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
    
    public function getSubjectAction($subject = null){
        try{
            $Subject = null;
            if($subject != null){
                $Subject = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                            ->getOneSubjectBySlug($subject);
            }
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array(
                        'subject'=> $subject,
                        'Subject' => $Subject
                    ));
        } catch (Exception $ex) {
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
}
