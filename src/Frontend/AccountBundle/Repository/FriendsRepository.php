<?php

namespace Frontend\AccountBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FriendsRepository extends EntityRepository{
    
    public function getUserFriendsByUser($user){
        return $this->createQueryBuilder('friends')
                ->select('friends')
                ->where("friends.status = 'active'")
                ->andWhere("friends.userA = :user or friends.userB = :user")
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
    }
    
    public function getFriendsStatus($MyUser, $viewedUser){
        return $this->createQueryBuilder('friends')
                ->select('friends')
                ->where("(userA = :myUser and userB = :viewedUser)")
                ->orWhere("userA = :viewedUser and userB = :myUser")
                ->join('friends.userA', 'userA')
                ->join('friends.userB', 'userB')
                ->setParameter('myUser', $MyUser)
                ->setParameter('viewedUser', $viewedUser)
                ->getQuery()
                ->getOneOrNullResult();
    }
    
}
