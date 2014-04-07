<?php

namespace Frontend\SubjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubjectFile
 *
 * @ORM\Table(name="subject_file")
 * @ORM\Entity
 */
class SubjectFile
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
     * @var integer
     *
     * @ORM\Column(name="file_id", type="bigint", nullable=false)
     */
    private $fileId;

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_id", type="integer", nullable=false)
     */
    private $subjectId;

    /**
     * @ORM\ManyToOne(targetEntity="Frontend\SubjectBundle\Entity\Subject", inversedBy="subjectFile")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    protected $subject;

    /**
     * @ORM\ManyToOne(targetEntity="Frontend\SubjectBundle\Entity\File", inversedBy="subjectFile")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    protected $file;


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
     * Set fileId
     *
     * @param integer $fileId
     * @return SubjectFile
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return integer 
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set subjectId
     *
     * @param integer $subjectId
     * @return SubjectFile
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    /**
     * Get subjectId
     *
     * @return integer 
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * Set subject
     *
     * @param \Frontend\SubjectBundle\Entity\Subject $subject
     * @return SubjectFile
     */
    public function setSubject(\Frontend\SubjectBundle\Entity\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \Frontend\SubjectBundle\Entity\Subject 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set file
     *
     * @param \Frontend\SubjectBundle\Entity\File $file
     * @return SubjectFile
     */
    public function setFile(\Frontend\SubjectBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Frontend\SubjectBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }
}
