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
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\Subject", mappedBy="user")
     */
    protected $subjects;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\LayoutBundle\Entity\Visitors", mappedBy="user")
     */
    protected $userVisits;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\File", mappedBy="user")
     */
    protected $files;
    
    /**
     * @ORM\OneToMany(targetEntity="\Frontend\IndexBundle\Entity\GuestBook", mappedBy="user")
     */
    protected $scraps;
    


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

    /**
     * Add subjects
     *
     * @param \Frontend\LayoutBundle\Entity\UserSettings $subjects
     * @return User
     */
    public function addSubject(\Frontend\LayoutBundle\Entity\UserSettings $subjects)
    {
        $this->subjects[] = $subjects;

        return $this;
    }

    /**
     * Remove subjects
     *
     * @param \Frontend\LayoutBundle\Entity\UserSettings $subjects
     */
    public function removeSubject(\Frontend\LayoutBundle\Entity\UserSettings $subjects)
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
}
