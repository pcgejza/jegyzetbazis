<?php

namespace Frontend\MessagingBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository{
    
    public function getMessagesByUser($User, $offset, $limit, $type, $getCountFromAll = false){
        $Messages = $this->createQueryBuilder('message');
                if($getCountFromAll == TRUE){
                    $Messages = $Messages
                            ->select('COUNT(message.id)');
                }else{
                    $Messages = $Messages
                        ->select('message')
                        ->addSelect('userA')
                        ->addSelect('userB')
                        ->addSelect('userAsettings')
                        ->addSelect('userBsettings')
                        ->addSelect('parent')
                        ->addSelect('children');
                }
                
                if($type == 'received'){
                    $Messages = $Messages 
                        ->where('userB = :MyUser AND (SELECT COUNT(m.id) FROM FrontendMessagingBundle:Message m WHERE m.parent = message) = 0')
                        ->groupBy('userA');
                    
                }elseif($type == 'sent'){
                    $Messages = $Messages 
                         ->where('userA = :MyUser AND (SELECT COUNT(m.id) FROM FrontendMessagingBundle:Message m WHERE m.parent = message) = 0')
                          ;
                        /**
                         * 
                         * ITT TARTOTTAM!!!!! FIXME::: 
                         * 
                         *valamiért nem tudom úgy össze group-by-olni hogy az azonoos parent-el rendelkezőket ne listázza többször...
                         */
                }
            $Messages =  $Messages     
                ->join('message.userA', 'userA')
                ->join('message.userB', 'userB')
                ->join('userA.userSettings', 'userAsettings')
                ->join('userB.userSettings', 'userBsettings')
                ->leftJoin('message.children', 'children')
                ->leftJoin('message.parent', 'parent')
                
                ->orderBy('message.sendDate', 'DESC')
                ->setParameter('MyUser', $User);
            if($getCountFromAll == TRUE){
                 $Messages =  $Messages->getQuery()->getSingleScalarResult();
            }else{ 
                $Messages =  $Messages   
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
            }
        return $Messages;
    }
    
    
}
