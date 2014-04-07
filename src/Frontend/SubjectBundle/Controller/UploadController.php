<?php

namespace Frontend\SubjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;

use Frontend\SubjectBundle\Entity\File;
use Frontend\SubjectBundle\Form\Type\UploadImageFormType;

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
                    $kie .= "--> ".$oneFile->upload();
            }
            return new \Symfony\Component\HttpFoundation\Response($kie);
        }
        
        return $this->render('FrontendSubjectBundle:Upload:uploadForm.html.twig', array('form' => $form->createView()));
    }
}