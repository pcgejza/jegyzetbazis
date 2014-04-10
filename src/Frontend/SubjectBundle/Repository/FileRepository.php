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
    
}