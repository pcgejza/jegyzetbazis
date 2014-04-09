<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

use Frontend\SubjectBundle\Entity\File;
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
            
            if($f == NULL){
                throw new Exception('Null a file!');
            }
            
            $oneFile = new File();
            $oneFile->setFile($f);
            
            if(!$oneFile->upload())
                throw new Exception('Hiba a feltöltés során!');
            
            
            return new JsonResponse(array('success' => true, 'wp'=>$oneFile->getWebPath()));
        }catch(Exception $ex){
            return new JsonResponse(array('err' => $ex->getMessage()));
        }
    }
}