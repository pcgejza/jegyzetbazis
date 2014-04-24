<?php

namespace Frontend\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity
 */
class Avatar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

     /**
     * @ORM\ManyToOne(targetEntity="\Frontend\LayoutBundle\Entity\User", inversedBy="avatar")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';
    
    /**
     * @ORM\OneToOne(targetEntity="\Frontend\LayoutBundle\Entity\UserSettings", mappedBy="avatar")
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
     * Set userId
     *
     * @param integer $userId
     * @return Avatar
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Avatar
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     * @return Avatar
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    /**
     * Get insertDate
     *
     * @return \DateTime 
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Avatar
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \Frontend\LayoutBundle\Entity\User $user
     * @return Avatar
     */
    public function setUser(\Frontend\LayoutBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Frontend\LayoutBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userSettings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userSettings
     *
     * @param \Frontend\LayoutBundle\Entity\UserSettings $userSettings
     * @return Avatar
     */
    public function addUserSetting(\Frontend\LayoutBundle\Entity\UserSettings $userSettings)
    {
        $this->userSettings[] = $userSettings;

        return $this;
    }

    /**
     * Remove userSettings
     *
     * @param \Frontend\LayoutBundle\Entity\UserSettings $userSettings
     */
    public function removeUserSetting(\Frontend\LayoutBundle\Entity\UserSettings $userSettings)
    {
        $this->userSettings->removeElement($userSettings);
    }

    /**
     * Get userSettings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }
}
