<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

use Frontend\SubjectBundle\Entity\File;
use Frontend\SubjectBundle\Entity\Subject;
use Frontend\SubjectBundle\Entity\SubjectFile;
use Frontend\SubjectBundle\Form\Type\UploadImageFormType;

use \Symfony\Component\HttpFoundation\Request;

class UploadController extends Controller
{
    /*
     * A kép feltöltő ablak lekérdezése
     */
    public function getUploadWindowAction(){
        return $this->render('FrontendSubjectBundle:Upload:uploadWindow.html.twig');
    }
    
    public function uploadFileAction(\Symfony\Component\HttpFoundation\Request $request){
        
        $File = new File();
        $form = $this->createForm(new UploadImageFormType(), $File);
        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($request);
            $kie = "";
            foreach ($request->files->get('files') as $file) {
                    $oneFile = new File();
                    $oneFile->setFile($file);
                    var_dump($oneFile->upload());
            }
            return new \Symfony\Component\HttpFoundation\Response($kie);
        }
        
        return $this->render('FrontendSubjectBundle:Upload:uploadForm.html.twig', array('form' => $form->createView()));
    }
    
    public function uploadOneFileAction(Request $request){
        try{
            $f = $request->files->get('file');
            $filename = $request->request->get('filename');
            $subjects = $request->request->get('subjects');
            
            $user = $this->get('security.context')->getToken()->getUser();
            
            if($f == NULL){
                throw new Exception('Null a file!');
            }
            
            $oneFile = new File();
            $oneFile->setFile($f);
            $oneFile->setUser($user);
            $filename = strlen($filename) > 0 ? $filename : $oneFile->getPath();
            $oneFile->setName($filename);
            
            if(!$oneFile->upload($user))
                throw new Exception('Hiba a feltöltés során!');
            
            
            $oneFile->setUploadedTime(new \DateTime('now'));
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($oneFile);
            
            if(sizeof($subjects)>0){
                $Subjects = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                            ->getSubjectsByNamesArray($subjects);
                if(sizeof($Subjects) != sizeof($subjects)){
                    $NewSubjects = array();
                    foreach($subjects as $sub){
                        $van = false;
                        foreach($Subjects as $S){
                            if($S->getName() == $sub)
                                $van = true;
                        }
                        if(!$van){
                            $NewSubject = new Subject();
                            $NewSubject->setName($sub);
                            
                            $slug = strtolower($sub);
                            // FIXME: A gedmo valamiért nem működik..
                            $NewSubject->setSlug($slug);
                            $NewSubject->setUser($user);
                            $NewSubject->setStatus(0);
                            $NewSubject->setInsertDate(new \DateTime());
                            $em->persist($NewSubject);
                            $NewSubjects[] = $NewSubject;
                        }
                    }
                    $ALLSubjects = array_merge($Subjects, $NewSubjects);
                }else{
                    $ALLSubjects = $Subjects;
                }
                foreach($ALLSubjects as $sub){
                    $SubjectFile = new SubjectFile();
                    $SubjectFile->setFile($oneFile);
                    $SubjectFile->setSubject($sub);
                    $sub->setUpdateDate(new \DateTime());
                    $em->persist($SubjectFile);
                    $em->persist($sub);
                }
            }
            
            $em->flush();
            
            return new JsonResponse(array(
                'id' => $oneFile->getId(),
                'success' => true, 
                'wp'=>$oneFile->getWebPath(),
                'path' => $oneFile->getPath()    
                    ));
        }catch(Exception $ex){
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
    
    
    //tantárgy mező módosítás esetén kerül meghívásra
    public function updateSubjectsAction(Request $request){
        try{
            $subject = $request->request->get('subject');
            $fileIds = $request->request->get('fileIds');
            $type = $request->request->get('type');
            
            $user = $this->get('security.context')->getToken()->getUser();
            
            if($subject==null || sizeof($subject) < 1)
                throw new Exception('Hiba van a subjectNames-el');
            
            if($fileIds == null && !is_array($fileIds))
                throw new Exception('Hiba van a fileIds-el');
            
            if($type == null)
                throw new Exception('Hiba van a type-el');
            
            $Files = $this->getDoctrine()->getRepository('FrontendSubjectBundle:File')
                        ->getFilesByIdsArray($fileIds);
            
            $em = $this->getDoctrine()->getEntityManager();
            
            $Subject = $this->getDoctrine()->getRepository('FrontendSubjectBundle:Subject')
                         ->getOneOrNullByName($subject['name']);
            if($type == 'add'){
                if($Subject==NULL){
                     $Subject = new Subject();
                     $Subject->setName($subject['name']);
                     $Subject->setUser($user);
                     $Subject->setStatus(0);
                     $Subject->setInsertDate(new \DateTime());
                     $em->persist($Subject);
                }
                foreach($Files as $f){
                    $SubjectFile = new SubjectFile();
                    $SubjectFile->setFile($f);
                    $SubjectFile->setSubject($Subject);
                    $em->persist($SubjectFile);
                }
            }elseif($type == 'remove'){
                 $SubjectFiles = $this->getDoctrine()->getRepository('FrontendSubjectBundle:SubjectFile')
                             ->getSubjectFilesByFilesArrObjAndSubjectObj($Files, $Subject);

                 foreach ($SubjectFiles as $sf){
                     $em->remove($sf);
                 }
            }
            
            $em->flush();
            
            return new JsonResponse(array());
        } catch (Exception $ex) {
            return new JsonResponse(array('err'=>$ex->getMessage()));
        }
    }
}