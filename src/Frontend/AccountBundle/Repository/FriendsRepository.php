<?php

namespace Frontend\AccountBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FriendsRepository extends EntityRepository{
    
    public function getUserFriendsByUser($user){
        return $this->createQueryBuilder('friends')
                ->select('friends')
                ->addSelect('uA, uB')
                ->addSelect('uAs, uBs')
                ->where("friends.status = 'active'")
                ->andWhere("friends.userA = :user or friends.userB = :user")
                ->join('friends.userA', 'uA')
                ->join('friends.userB', 'uB')
                ->join('uA.userSettings', 'uAs')
                ->join('uB.userSettings', 'uBs')
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
    
    public function isFriends($UserA, $UserB){
       $f = $this->getFriendsStatus($UserA, $UserB);
       
       if($f == NULL) return false;
       
       if($f->getStatus() != 'active') return false;
       else return true;
       
    }
    
    public function getAllActiveFriendsCount($User){
        $count = $this->createQueryBuilder('friend')
                ->select('COUNT(friend.id)')
                ->where("friend.status = 'active'")
                ->andWhere('(friend.userA = :user OR friend.userB = :user)')
                ->setParameter('user', $User)
                ->getQuery()
                ->getSingleScalarResult();
        
        
        return $count;
    }
    
}
