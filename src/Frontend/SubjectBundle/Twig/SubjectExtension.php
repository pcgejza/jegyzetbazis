<?php

namespace Frontend\SubjectBundle\Twig;
use Gregwar\Image\Image;

class SubjectExtension extends \Twig_Extension{
    
    /*
    public function getFilters(){
            return array(
                    new \Twig_SimpleFilter('text', array($this, 'blurText')),
            );
    }
    
     * 
     */
    
    public function getFunctions()
    {
        return array(
            'getImageByPath'  => new \Twig_Function_Method($this, 'getImageByPath'),
        );
    }
    
    public function getImageByPath($path){
        $extension = substr($path, strrpos($path, '.')+1);
        $openImagePath = $path;
        switch($extension){
            case 'png':
            case 'bmp':
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'png':
            case 'tif':
                $openImagePath = $path;
                break;
            case 'pdf':
                $openImagePath = 'images/pdf_icon.png';
                break;
            case 'docx':
            case 'doc':
                $openImagePath = 'images/doc_icon.png';
                break;
            case 'mp3':
            case 'wav':
            case 'ogg':
            case 'wma':
                $openImagePath = 'images/audio_icon.png';
                break;
            case 'mp4':
            case 'avi':
            case 'mpeg':
            case 'wmv':
            case 'mkv':
                $openImagePath = 'images/video_icon.png';
                break;
            default :
                $openImagePath = 'images/file_icon.png';
        }
       // $image = '<img src="/'.Image::open($openImagePath).'">';
        return $openImagePath;
    }

    public function getName() {
        return 'subject_extension';
    }

}   
       