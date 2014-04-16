<?php

namespace Frontend\SubjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="Frontend\SubjectBundle\Repository\SubjectRepository")
 */
class Subject
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
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_user_id", type="integer", nullable=true)
     */
    private $createdUserId;
    
     /**
     * @ORM\ManyToOne(targetEntity="\Frontend\LayoutBundle\Entity\User", inversedBy="subjects")
     * @ORM\JoinColumn(name="created_user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @ORM\OneToMany(targetEntity="\Frontend\SubjectBundle\Entity\SubjectFile", mappedBy="subject")
     */
    protected $subjectFile;


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
     * @return Subject
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
     * Set description
     *
     * @param string $description
     * @return Subject
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     * @return Subject
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
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Subject
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set createdUserId
     *
     * @param integer $createdUserId
     * @return Subject
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;

        return $this;
    }

    /**
     * Get createdUserId
     *
     * @return integer 
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Subject
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
     * @return Subject
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
     * Set slug
     *
     * @param string $slug
     * @return Subject
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subjectFile = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subjectFile
     *
     * @param \Frontend\SubjectBundle\Entity\SubjectFile $subjectFile
     * @return Subject
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
}
