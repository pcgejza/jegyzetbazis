<?php

namespace Frontend\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="\Frontend\MessagingBundle\Repository\MessageRepository")
 */
class Message
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
     * @var integer
     *
     * @ORM\Column(name="user_id_b", type="integer", nullable=false)
     */
    private $userIdB;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=false)
     */
    private $sendDate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="see_time", type="datetime", nullable=true)
     */
    private $seeTime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status = 'active';

    /**
     *  @ORM\ManyToOne(targetEntity="\Frontend\AccountBundle\Entity\User", inversedBy="messageA")
     *  @ORM\JoinColumn(name="user_id_a", referencedColumnName="id")
     */
    private $userA;

    /**
     *  @ORM\ManyToOne(targetEntity="\Frontend\AccountBundle\Entity\User", inversedBy="messageB")
     *  @ORM\JoinColumn(name="user_id_B", referencedColumnName="id")
     */
    private $userB;


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
     * @return Message
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
     * @return Message
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
     * Set sendDate
     *
     * @param \DateTime $sendDate
     * @return Message
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime 
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set seeTime
     *
     * @param \DateTime $seeTime
     * @return Message
     */
    public function setSeeTime($seeTime)
    {
        $this->seeTime = $seeTime;

        return $this;
    }

    /**
     * Get seeTime
     *
     * @return \DateTime 
     */
    public function getSeeTime()
    {
        return $this->seeTime;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Message
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
     * @param \Frontend\AccountBundle\Entity\User $userA
     * @return Message
     */
    public function setUserA(\Frontend\AccountBundle\Entity\User $userA = null)
    {
        $this->userA = $userA;

        return $this;
    }

    /**
     * Get userA
     *
     * @return \Frontend\AccountBundle\Entity\User 
     */
    public function getUserA()
    {
        return $this->userA;
    }

    /**
     * Set userB
     *
     * @param \Frontend\AccountBundle\Entity\User $userB
     * @return Message
     */
    public function setUserB(\Frontend\AccountBundle\Entity\User $userB = null)
    {
        $this->userB = $userB;

        return $this;
    }

    /**
     * Get userB
     *
     * @return \Frontend\AccountBundle\Entity\User 
     */
    public function getUserB()
    {
        return $this->userB;
    }
}
