<?php

namespace Frontend\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubjectFilesRepository extends EntityRepository{
    
    
    public function getSubjectFilesByFilesArrObjAndSubjectObj($filesObjArr, $subjectObj){
        return $this->createQueryBuilder('subjectFile')
                ->select('subjectFile')
                ->where('subjectFile.file IN(:files)')
                ->andWhere("subjectFile.subject = :subject")
                ->setParameter('files', $filesObjArr)
                ->setParameter('subject', $subjectObj)
                ->getQuery()
                ->getResult();
    }
}