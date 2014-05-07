<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

class DefaultController extends Controller
{
    public function indexAction($subject = NULL)
    {
        $page = $this->get('request')->query->get('page') ? $this->get('request')->query->get('page') : 1;
       $sortBy = $this->get('request')->query->get('sortBy') ? $this->get('request')->query->get('sortBy') : null;
       
        if($this->get('request')->getMethod() === 'POST'){
            $page = $this->get('request')->request->get('page');
            $sortBy = $this->get('request')->request->get('sortBy');
            return $this->getSubjectAction($subject, $page, $sortBy);
        }
        
        return $this->render('FrontendSubjectBundle:Default:index.html.twig',
                array(
                    'subject'=>$subject,
                    'page' => $page,
                    'sortBy' => $sortBy
                    ));
    }
    
    public function getAllSubjectsAction($subject = null){
        try{
            $Subjects = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                            ->getAllActiveSubjects();
                    
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
    
    public function getSubjectAction($subject = null, $page = null, $sortBy = null){
        try{
            $Subject = null;
            $Files = null;
            $User = $this->get('security.context')->getToken()->getUser();
            if($subject != null){
                $Subject = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                            ->getOneSubjectBySlug($subject);
                
                $filters = array();
                if($sortBy !== NULL){
                    $filters['sortBy'] = $sortBy;
                }
                
                $FilesQuery = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
                            ->getFilesToPageBySubjectQUERY($Subject, $User, $filters);
                
                $paginator  = $this->get('knp_paginator');
                $Files = $paginator->paginate($FilesQuery,$page,10);
            }
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array(
                        'subject'=> $subject,
                        'Subject' => $Subject,
                        'Files' => $Files
                    ));
        } catch (Exception $ex) {
            return $this->render('FrontendSubjectBundle:Subject:single.html.twig',
                    array('err' => $ex->getMessage()));
        }
    }
}
