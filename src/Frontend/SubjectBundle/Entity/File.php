<?php

namespace Frontend\SubjectBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Frontend\SubjectBundle\Repository\FileRepository")
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded_time", type="datetime", nullable=false)
     */
    private $uploadedTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;
    
     /**
     * @ORM\ManyToOne(targetEntity="\Frontend\LayoutBundle\Entity\User", inversedBy="files")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="download_count", type="integer", nullable=false)
     */
    private $downloadCount = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';


    /**
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\SubjectFile", mappedBy="file")
     */
    protected $subjectFile;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subjectFile = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return File
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
     * Set path
     *
     * @param string $path
     * @return File
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
     * Set uploadedTime
     *
     * @param \DateTime $uploadedTime
     * @return File
     */
    public function setUploadedTime($uploadedTime)
    {
        $this->uploadedTime = $uploadedTime;

        return $this;
    }

    /**
     * Get uploadedTime
     *
     * @return \DateTime 
     */
    public function getUploadedTime()
    {
        return $this->uploadedTime;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return File
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
     * Set downloadCount
     *
     * @param integer $downloadCount
     * @return File
     */
    public function setDownloadCount($downloadCount)
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    /**
     * Get downloadCount
     *
     * @return integer 
     */
    public function getDownloadCount()
    {
        return $this->downloadCount;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return File
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
     * Add subjectFile
     *
     * @param \Frontend\SubjectBundle\Entity\SubjectFile $subjectFile
     * @return File
     */
    public function addSubjectFile(\Frontend\SubjectBundle\Entity\SubjectFile $subjectFile)
    {
        $this->subjectFile[] = $subjectFile;

        return $this;
    }

    /**
     * Remove subjectFile
     *
     * @param \Frontend\SubjectBundle\Entity\SubjectFile $subjectFile
     */
    public function removeSubjectFile(\Frontend\SubjectBundle\Entity\SubjectFile $subjectFile)
    {
        $this->subjectFile->removeElement($subjectFile);
    }

    /**
     * Get subjectFile
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubjectFile()
    {
        return $this->subjectFile;
    }
    
    
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

    public function getWebPath2()
    {
        return null === $this->path
            ? null
            : '/symfony/web/'.$this->getUploadDir().'/'.$this->path;
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
        return 'uploads/'.$this->getUser()->getId();
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
    
    public function upload($user){
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
     * Set user
     *
     * @param \Frontend\LayoutBundle\Entity\User $user
     * @return File
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
}
