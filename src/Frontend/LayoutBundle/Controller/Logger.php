<?php

namespace Frontend\LayoutBundle\Controller;

use Frontend\LayoutBundle\Entity\Log;
use Doctrine\ORM\EntityManager;

class Logger{

    private $User;
    private $sessionID;
    private $type;
    private $value;
    private $log;
    
    public function __construct($em) {
        $this->em = $em;
        $this->log = new Log();
        
    }
   
    public function exec(){
        $this->em->flush($this->log);
        $this->em->persist();
    }
    
    public function getUser() {
        return $this->User;
    }

    public function getSessionID() {
        return $this->sessionID;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return $this->value;
    }

    public function setUser($User) {
        $this->log->setUser($User);
        $this->User = $User;
        
        return $this;
    }

    public function setSessionID($sessionID) {
        $this->log->setSessionId($sessionID);
        $this->sessionID = $sessionID;
        return $this;
    }

    public function setType($type) {
        $this->log->setType($type);
        $this->type = $type;
        return $this;
    }

    public function setValue($value) {
        $this->log->setValue($value);
        $this->value = $value;
        return $this;
    }
}
