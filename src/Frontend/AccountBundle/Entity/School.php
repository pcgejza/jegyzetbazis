<?php

namespace Frontend\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * School
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="\Frontend\AccountBundle\Repository\SchoolRepository")
 */
class School
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="creator_user_id", type="integer", nullable=false)
     */
    private $creatorUserId;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '1';

    /**
     * @var \Frontend\AdminBundle\Entity\User
     * @Gedmo\Blameable(on="create")
     *  @ORM\ManyToOne(targetEntity="\Frontend\AccountBundle\Entity\User", inversedBy="createdSchools")
     *  @ORM\JoinColumn(name="creator_user_id", referencedColumnName="id")
     */
    private $creatorUser;
    
    /**
     *  @ORM\OneToMany(targetEntity="UserSettings", mappedBy="school")
     */
    private $userSettings;

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
     * Set name
     *
     * @param string $name
     * @return School
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set creatorUserId
     *
     * @param integer $creatorUserId
     * @return School
     */
    public function setCreatorUserId($creatorUserId)
    {
        $this->creatorUserId = $creatorUserId;

        return $this;
    }

    /**
     * Get creatorUserId
     *
     * @return integer 
     */
    public function getCreatorUserId()
    {
        return $this->creatorUserId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return School
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return School
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set creatorUser
     *
     * @param \Frontend\AccountBundle\Entity\User $creatorUser
     * @return School
     */
    public function setCreatorUser(\Frontend\AccountBundle\Entity\User $creatorUser = null)
    {
        $this->creatorUser = $creatorUser;

        return $this;
    }

    /**
     * Get creatorUser
     *
     * @return \Frontend\AccountBundle\Entity\User 
     */
    public function getCreatorUser()
    {
        return $this->creatorUser;
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
     * @param \Frontend\AccountBundle\Entity\UserSettings $userSettings
     * @return School
     */
    public function addUserSetting(\Frontend\AccountBundle\Entity\UserSettings $userSettings)
    {
        $this->userSettings[] = $userSettings;

        return $this;
    }

    /**
     * Remove userSettings
     *
     * @param \Frontend\AccountBundle\Entity\UserSettings $userSettings
     */
    public function removeUserSetting(\Frontend\AccountBundle\Entity\UserSettings $userSettings)
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
