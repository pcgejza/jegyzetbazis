<?php
namespace Frontend\LayoutBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Frontend\LayoutBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    
    private $name;
    
    public function getName(){
        return $this->name;
    }
    
    /**
     * @ORM\OneToOne(targetEntity="\Frontend\LayoutBundle\Entity\UserSettings", mappedBy="user")
     */
    protected $userSettings;
    


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userSettings
     *
     * @param \Frontend\LayoutBundle\Entity\UserSettings $userSettings
     * @return User
     */
    public function setUserSettings(\Frontend\LayoutBundle\Entity\UserSettings $userSettings = null)
    {
        $this->userSettings = $userSettings;

        return $this;
    }

    /**
     * Get userSettings
     *
     * @return \Frontend\LayoutBundle\Entity\UserSettings 
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }
}
