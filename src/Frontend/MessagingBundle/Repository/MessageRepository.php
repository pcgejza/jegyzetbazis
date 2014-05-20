<?php

namespace Frontend\MessagingBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository{
    
    public function getMessagesByUser($User, $offset, $limit, $type, $getCountFromAll = false){
        
        $HelperQuery = $this->createQueryBuilder('m')
                    ->select('GROUP_CONCAT(m)')
                    ->where('userB = :us')
                    ->andWhere('m.parentId is not null')
                    ->join('m.userB', 'userB')
                    ->leftJoin('m.parent', 'parent')
                    ->setParameter('us', $User)
                    ->groupBy('m.parent')
                    ->orderBy('m.sendDate', 'DESC') 
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
        
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
                        ->groupBy('message.parentId')
                        ->addGroupBy('message.id')
                            ;
                    
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
    
    public function getOneById($id){
        
        $msg = $this->createQueryBuilder('message')
                  ->select('message')
                  ->addSelect('uA')
                  ->addSelect('uB')
                  ->addSelect('uAs')
                  ->addSelect('uBs')
                  ->addSelect('par')
                  ->addSelect('child')
                ->where("message.id = $id")
                ->leftJoin('message.children','child')
                ->leftJoin('message.parent','par')
                  ->join('message.userA', 'uA')
                  ->join('message.userB', 'uB')
                  ->join('uA.userSettings', 'uAs')
                  ->join('uB.userSettings', 'uBs')
                ->getQuery()->getOneOrNullResult();
        
        if($msg == NULL) return NULL;
        
        if($msg->getParent() != NULL){
            //van szülője
            $parentID = $msg->getParent()->getId();
            $Return =  $this->createQueryBuilder('message')
                  ->select('message')
                  ->addSelect('uA')
                  ->addSelect('uB')
                  ->addSelect('uAs')
                  ->addSelect('uBs')
                  ->addSelect('par')
                  ->addSelect('child')
                ->where("message.id = $parentID or message.parentId = $parentID")
                ->leftJoin('message.children','child')
                ->leftJoin('message.parent','par')
                  ->join('message.userA', 'uA')
                  ->join('message.userB', 'uB')
                  ->join('uA.userSettings', 'uAs')
                  ->join('uB.userSettings', 'uBs')
                    ->orderBy('message.sendDate', 'ASC')
                ->getQuery()->getResult();
        }elseif(sizeof($msg->getChildren())>0){
            // vannak gyerekei
            $Return =  $this->createQueryBuilder('message')
                  ->select('message')
                  ->addSelect('uA')
                  ->addSelect('uB')
                  ->addSelect('uAs')
                  ->addSelect('uBs')
                  ->addSelect('par')
                  ->addSelect('child')
                ->where("message.id = $id or message.parentId = $id")
                ->leftJoin('message.children','child')
                ->leftJoin('message.parent','par')
                  ->join('message.userA', 'uA')
                  ->join('message.userB', 'uB')
                  ->join('uA.userSettings', 'uAs')
                  ->join('uB.userSettings', 'uBs')
                    ->orderBy('message.sendDate', 'ASC')
                ->getQuery()->getResult();
        }else{
            // nincs semmilye, úgy ahogy van visszaadni
            $Return = array($msg);
        }
        
        
        
        
        return $Return;
    }
    
    public function getNoReadMessagesCountByUser($User){
        return $this->createQueryBuilder('m')
                ->select('COUNT(m.id)')
                ->where('uB = :User')
                ->andWhere('m.seeTime is null')
                ->leftJoin('m.userA', 'uA')
                ->leftJoin('m.userB', 'uB')
                ->setParameter('User', $User)
                ->getQuery()
                ->getSingleScalarResult();
    }
}
