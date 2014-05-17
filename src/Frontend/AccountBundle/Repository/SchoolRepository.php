<?php
namespace Frontend\AccountBundle\Repository;

use Doctrine\ORM\EntityRepository;


class SchoolRepository extends EntityRepository{
   
    
    public function getAllActiveSchools(){
        return $this->createQueryBuilder('school')
                 ->select('school')
                 ->addSelect('user')
                 ->where('school.status = 1')
                 ->leftJoin('school.creatorUser', 'user')
                 ->getQuery()
                 ->getResult();
    }
    
    public function getAllSchools(){
        return $this->createQueryBuilder('school')
                 ->select('school')
                 ->addSelect('user')
                 ->leftJoin('school.creatorUser', 'user')
                 ->getQuery()
                 ->getResult();
    }
    
    public function getSchoolOrNullByName($name){
        return $this->createQueryBuilder('school')
                ->select('school')
                ->where('school.name = :name')
                ->addSelect('user')
                ->leftJoin('school.creatorUser', 'user')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
}
