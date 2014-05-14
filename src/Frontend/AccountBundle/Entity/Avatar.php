<?php

namespace Frontend\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
    
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity="\Frontend\AccountBundle\Entity\User", inversedBy="avatar")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * @ORM\OneToOne(targetEntity="\Frontend\AccountBundle\Entity\UserSettings", mappedBy="avatar")
     */
    protected $userSettings;
    

    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars/'.$this->getUser()->getId();
    }
    
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function upload(){
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return false;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        
        $fileOriginal = $this->getFile()->getClientOriginalName();
        $extraIndex = 1;
        $this->path = $fileOriginal;
        $pathInf = pathinfo($this->getAbsolutePath());
        $fileOriginal = $pathInf['filename'] . "." . $pathInf['extension'];

        while (file_exists($this->getAbsolutePath())) {
            $fileOriginal = $pathInf['filename'] . "-$extraIndex" . "." . $pathInf['extension'];
            $extraIndex++;
            $this->path = $fileOriginal;
        }

        $this->getFile()->move(
                $this->getUploadRootDir(), $fileOriginal
        );


        // set the path property to the filename where you've saved the file

        // clean up the file property as you won't need it anymore
        $this->file = null;
        
        return true;
    }


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
     * @param \Frontend\AccountBundle\Entity\User $user
     * @return Avatar
     */
    public function setUser(\Frontend\AccountBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Frontend\AccountBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    

    /**
     * Set userSettings
     *
     * @param \Frontend\AccountBundle\Entity\UserSettings $userSettings
     * @return Avatar
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
}
