<?php

namespace Frontend\AccountBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{
    
    public function findOneByNickOrEmail($n){
        return $this->createQueryBuilder('user')
                ->select('user')
                ->where('(user.username = :n) or (user.email = :n)')
                ->setParameter('n', $n)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
    public function findOneUserById($ID){
        return $this->createQueryBuilder('user')
                ->select('user')
                ->addSelect('userSettings')
                ->addSelect('avatar')
                ->where('user.id = :id')
                ->leftJoin('user.userSettings', 'userSettings')
                ->leftJoin('userSettings.avatar', 'avatar')
                ->setParameter('id', $ID)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
}
