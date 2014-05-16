<?php

namespace Frontend\MessagingBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository{
    
    public function getMessagesByUser($User, $offset, $limit, $type){
        $Messages = $this->createQueryBuilder('message')
                ->select('message')
                ->addSelect('userA')
                ->addSelect('userB')
                ->addSelect('userAsettings')
                ->addSelect('userBsettings');
                
        //beérkező
        
        /**
         * 
         * ITT TARTOTTAM!!!!! FIXME::: LISTÁZZA AZ ÜZENETEK KÜLDŐJÉT, viszont nem a legfrissebb üzenet lesz felül
         */
                if($type == 'received'){
                    $Messages = $Messages 
                        ->where('userB = :MyUser')
                        ->groupBy('userA')
                            ;
                }
            $Messages =  $Messages     
                ->join('message.userA', 'userA')
                ->join('message.userB', 'userB')
                ->join('userA.userSettings', 'userAsettings')
                ->join('userB.userSettings', 'userBsettings')
                
                ->orderBy('message.sendDate', 'DESC')
                ->setParameter('MyUser', $User)
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        
        return $Messages;
    }
    
    
}
