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
    
    public function getFilesToPageBySubject($Subject, $user, $limit = 10){
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
    
}