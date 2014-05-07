<?php

namespace Frontend\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository{
    
    public function getFilesByIdsArray($array){
        return $this->createQueryBuilder('file')
                ->select('file,subjectFile')
                ->where('file.id IN(:ids)')
                ->setParameter('ids', $array)
                ->leftJoin('file.subjectFile', 'subjectFile')
                ->getQuery()
                ->getResult();
    }
    
    public function getFilesToPageBySubject($Subject, $user, $page=1, $limit = 10){
        $Files = $this->createQueryBuilder('file')
                ->select('file, user, userSettings')
                ->where('subjectFile.subject = :subject')
                ->join('file.subjectFile', 'subjectFile')
                ->leftJoin('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('subject', $Subject)
                ->orderBy('file.uploadedTime', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        
        return $Files;
    }
    public function getFilesToPageBySubjectQUERY($Subject, $user, $filters = array()){
        $Files = $this->createQueryBuilder('file')
                ->select('file, user, userSettings')
                ->where('subjectFile.subject = :subject')
                ->join('file.subjectFile', 'subjectFile')
                ->leftJoin('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('subject', $Subject)
                ->orderBy('file.uploadedTime', 'DESC');
        
       if(isset($filters['sortBy'])&& false){
           switch($filters['sortBy']){
               case 'showNew':
                  // $Files = $Files->orderBy('file.uploadedTime', 'DESC');
                   break;
               case 'showOld':
                    //$Files = $Files->orderBy('file.uploadedTime', 'ASC');
                   break;
               case '':
                   
                   break;
               case '':
                   
                   break;
           }
       }
        
        return $Files->getQuery();
    }
    
    public function getFilesCountByUser($User){
        return $this->createQueryBuilder('file')
                ->select('COUNT(file.id)')
                ->where('user = :user')
                ->join('file.user', 'user')
                ->setParameter('user', $User)
                ->getQuery()
                ->getSingleScalarResult();
    }
    
}