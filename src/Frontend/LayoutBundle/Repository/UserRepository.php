<?php

namespace Frontend\LayoutBundle\Repository;

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
}
