<?php

namespace Frontend\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProfileRepository
 *
 * Add your own custom repository methods below.
 */
class SubjectRepository extends EntityRepository{

    
    public function getAllSubjects(){
        return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->getQuery()
                ->getResult();
    }
    
    public function getOneSubjectBySlug($slug){
        return $this->createQueryBuilder('subject')
                ->select('subject, user, uSettings')
                ->leftJoin('subject.user', 'user')
                ->leftJoin('user.userSettings', 'uSettings')
                ->where('subject.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getOneOrNullResult();
    }
}