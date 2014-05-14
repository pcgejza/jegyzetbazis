<?php
namespace Frontend\AccountBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Frontend\AccountBundle\Repository\UserRepository")
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

    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="registration_time", type="datetime", nullable=true)
     */
    private $registrationTime;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->registrationTime = new \DateTime('now');
    }
    
    
    private $name;
    
    public function getName(){
        return $this->name;
    }
    
    /**
     * @ORM\OneToOne(targetEntity="\Frontend\AccountBundle\Entity\UserSettings", mappedBy="user")
     */
    protected $userSettings;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\Subject", mappedBy="user")
     */
    protected $subjects;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\LayoutBundle\Entity\Visitors", mappedBy="user")
     */
    protected $userVisits;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\LayoutBundle\Entity\Log", mappedBy="user")
     */
    protected $userLogs;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\File", mappedBy="user")
     */
    protected $files;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\IndexBundle\Entity\GuestBook", mappedBy="user")
     */
    protected $scraps;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\AccountBundle\Entity\Friends", mappedBy="userA")
     */
    protected $friendsA;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\AccountBundle\Entity\Friends", mappedBy="userB")
     */
    protected $friendsB;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\AccountBundle\Entity\Avatar", mappedBy="user")
     */
    protected $avatar;
    
    


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
     * @param \Frontend\AccountBundle\Entity\UserSettings $userSettings
     * @return User
     */
    public function setUserSettings(\Frontend\AccountBundle\Entity\UserSettings $userSettings = null)
    {
        $this->userSettings = $userSettings;

        return $this;
    }

    /**
     * Get userSettings
     *
     * @return \Frontend\AccountBundle\Entity\UserSettings 
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }

    /**
     * Add subjects
     *
     * @param \Frontend\AccountBundle\Entity\UserSettings $subjects
     * @return User
     */
    public function addSubject(\Frontend\AccountBundle\Entity\UserSettings $subjects)
    {
        $this->subjects[] = $subjects;

        return $this;
    }

    /**
     * Remove subjects
     *
     * @param \Frontend\AccountBundle\Entity\UserSettings $subjects
     */
    public function removeSubject(\Frontend\AccountBundle\Entity\UserSettings $subjects)
    {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add userVisits
     *
     * @param \Frontend\LayoutBundle\Entity\Visitors $userVisits
     * @return User
     */
    public function addUserVisit(\Frontend\LayoutBundle\Entity\Visitors $userVisits)
    {
        $this->userVisits[] = $userVisits;

        return $this;
    }

    /**
     * Remove userVisits
     *
     * @param \Frontend\LayoutBundle\Entity\Visitors $userVisits
     */
    public function removeUserVisit(\Frontend\LayoutBundle\Entity\Visitors $userVisits)
    {
        $this->userVisits->removeElement($userVisits);
    }

    /**
     * Get userVisits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserVisits()
    {
        return $this->userVisits;
    }

    /**
     * Add files
     *
     * @param \Frontend\SubjectBundle\Entity\File $files
     * @return User
     */
    public function addFile(\Frontend\SubjectBundle\Entity\File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \Frontend\SubjectBundle\Entity\File $files
     */
    public function removeFile(\Frontend\SubjectBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add scraps
     *
     * @param \Frontend\IndexBundle\Entity\GuestBook $scraps
     * @return User
     */
    public function addScrap(\Frontend\IndexBundle\Entity\GuestBook $scraps)
    {
        $this->scraps[] = $scraps;

        return $this;
    }

    /**
     * Remove scraps
     *
     * @param \Frontend\IndexBundle\Entity\GuestBook $scraps
     */
    public function removeScrap(\Frontend\IndexBundle\Entity\GuestBook $scraps)
    {
        $this->scraps->removeElement($scraps);
    }

    /**
     * Get scraps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScraps()
    {
        return $this->scraps;
    }

    /**
     * Add friendsA
     *
     * @param \Frontend\AccountBundle\Entity\Friends $friendsA
     * @return User
     */
    public function addFriendsA(\Frontend\AccountBundle\Entity\Friends $friendsA)
    {
        $this->friendsA[] = $friendsA;

        return $this;
    }

    /**
     * Remove friendsA
     *
     * @param \Frontend\AccountBundle\Entity\Friends $friendsA
     */
    public function removeFriendsA(\Frontend\AccountBundle\Entity\Friends $friendsA)
    {
        $this->friendsA->removeElement($friendsA);
    }

    /**
     * Get friendsA
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsA()
    {
        return $this->friendsA;
    }

    /**
     * Add friendsB
     *
     * @param \Frontend\AccountBundle\Entity\Friends $friendsB
     * @return User
     */
    public function addFriendsB(\Frontend\AccountBundle\Entity\Friends $friendsB)
    {
        $this->friendsB[] = $friendsB;

        return $this;
    }

    /**
     * Remove friendsB
     *
     * @param \Frontend\AccountBundle\Entity\Friends $friendsB
     */
    public function removeFriendsB(\Frontend\AccountBundle\Entity\Friends $friendsB)
    {
        $this->friendsB->removeElement($friendsB);
    }

    /**
     * Get friendsB
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsB()
    {
        return $this->friendsB;
    }

    /**
     * Add avatar
     *
     * @param \Frontend\AccountBundle\Entity\Avatar $avatar
     * @return User
     */
    public function addAvatar(\Frontend\AccountBundle\Entity\Avatar $avatar)
    {
        $this->avatar[] = $avatar;

        return $this;
    }

    /**
     * Remove avatar
     *
     * @param \Frontend\AccountBundle\Entity\Avatar $avatar
     */
    public function removeAvatar(\Frontend\AccountBundle\Entity\Avatar $avatar)
    {
        $this->avatar->removeElement($avatar);
    }

    /**
     * Get avatar
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add userLogs
     *
     * @param \Frontend\LayoutBundle\Entity\Log $userLogs
     * @return User
     */
    public function addUserLog(\Frontend\LayoutBundle\Entity\Log $userLogs)
    {
        $this->userLogs[] = $userLogs;

        return $this;
    }

    /**
     * Remove userLogs
     *
     * @param \Frontend\LayoutBundle\Entity\Log $userLogs
     */
    public function removeUserLog(\Frontend\LayoutBundle\Entity\Log $userLogs)
    {
        $this->userLogs->removeElement($userLogs);
    }

    /**
     * Get userLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserLogs()
    {
        return $this->userLogs;
    }

    /**
     * Set registrationTime
     *
     * @param \DateTime $registrationTime
     * @return User
     */
    public function setRegistrationTime($registrationTime)
    {
        $this->registrationTime = $registrationTime;

        return $this;
    }

    /**
     * Get registrationTime
     *
     * @return \DateTime 
     */
    public function getRegistrationTime()
    {
        return $this->registrationTime;
    }
}
