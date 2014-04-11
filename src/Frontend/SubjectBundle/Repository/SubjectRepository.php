<?php

namespace Frontend\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProfileRepository
 *
 * Add your own custom repository methods below.
 */
class SubjectRepository extends EntityRepository{

    
    public function getAllSubjects($orderBy = 'ASC'){
        return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->orderBy('subject.name', $orderBy)
                ->getQuery()
                ->getResult();
    }
    
    public function getAllActiveSubjects($orderBy = 'ASC'){
        return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->where('subject.status = 1')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->orderBy('subject.name', $orderBy)
                ->getQuery()
                ->getResult();
    }
    
    public function getOneSubjectBySlug($slug){
        return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->addSelect('subjectFile, file')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->leftJoin('subject.subjectFile', 'subjectFile')
                ->leftJoin('subjectFile.file', 'file')
                ->where('subject.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
    public function getOneOrNullByName($name){
          return $this->createQueryBuilder('subject')
                ->select('subject')
                ->where('subject.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
    public function getSubjectsByNamesArray($array){
            return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->where('subject.name IN(:names)')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->setParameter('names', $array)
                ->getQuery()
                ->getResult();
    }
    
    public function getSubjectsByIdsArray($array){
            return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->where('subject.id IN(:ids)')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->setParameter('ids', $array)
                ->getQuery()
                ->getResult();
    }
}