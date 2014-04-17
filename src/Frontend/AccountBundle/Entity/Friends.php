<?php

namespace Frontend\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friends
 *
 * @ORM\Table(name="friends")
 * @ORM\Entity(repositoryClass="Frontend\AccountBundle\Repository\FriendsRepository")
 */
class Friends
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
     * @ORM\Column(name="user_id_a", type="integer", nullable=false)
     */
    private $userIdA;
    
     /**
     * @ORM\ManyToOne(targetEntity="\Frontend\LayoutBundle\Entity\User", inversedBy="friendsA")
     * @ORM\JoinColumn(name="user_id_a", referencedColumnName="id")
     */
    protected $userA;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id_b", type="integer", nullable=false)
     */
    private $userIdB;
    
     /**
     * @ORM\ManyToOne(targetEntity="\Frontend\LayoutBundle\Entity\User", inversedBy="friendsB")
     * @ORM\JoinColumn(name="user_id_b", referencedColumnName="id")
     */
    protected $userB;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mark_date", type="datetime", nullable=false)
     */
    private $markDate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status = 'selected';



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
     * Set userIdA
     *
     * @param integer $userIdA
     * @return Friends
     */
    public function setUserIdA($userIdA)
    {
        $this->userIdA = $userIdA;

        return $this;
    }

    /**
     * Get userIdA
     *
     * @return integer 
     */
    public function getUserIdA()
    {
        return $this->userIdA;
    }

    /**
     * Set userIdB
     *
     * @param integer $userIdB
     * @return Friends
     */
    public function setUserIdB($userIdB)
    {
        $this->userIdB = $userIdB;

        return $this;
    }

    /**
     * Get userIdB
     *
     * @return integer 
     */
    public function getUserIdB()
    {
        return $this->userIdB;
    }

    /**
     * Set markDate
     *
     * @param \DateTime $markDate
     * @return Friends
     */
    public function setMarkDate($markDate)
    {
        $this->markDate = $markDate;

        return $this;
    }

    /**
     * Get markDate
     *
     * @return \DateTime 
     */
    public function getMarkDate()
    {
        return $this->markDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Friends
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set userA
     *
     * @param \Frontend\LayoutBundle\Entity\User $userA
     * @return Friends
     */
    public function setUserA(\Frontend\LayoutBundle\Entity\User $userA = null)
    {
        $this->userA = $userA;

        return $this;
    }

    /**
     * Get userA
     *
     * @return \Frontend\LayoutBundle\Entity\User 
     */
    public function getUserA()
    {
        return $this->userA;
    }

    /**
     * Set userB
     *
     * @param \Frontend\LayoutBundle\Entity\User $userB
     * @return Friends
     */
    public function setUserB(\Frontend\LayoutBundle\Entity\User $userB = null)
    {
        $this->userB = $userB;

        return $this;
    }

    /**
     * Get userB
     *
     * @return \Frontend\LayoutBundle\Entity\User 
     */
    public function getUserB()
    {
        return $this->userB;
    }
}
