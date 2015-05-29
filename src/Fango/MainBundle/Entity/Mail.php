<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mail
 */
class Mail
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $activeHour;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $mandrillId;

    /**
     * @var string
     */
    private $rejectReason;


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
     * Set email
     *
     * @param string $email
     * @return Mail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set activeHour
     *
     * @param string $activeHour
     * @return Mail
     */
    public function setActiveHour($activeHour)
    {
        $this->activeHour = $activeHour;

        return $this;
    }

    /**
     * Get activeHour
     *
     * @return string 
     */
    public function getActiveHour()
    {
        return $this->activeHour;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Mail
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
     * Set mandrillId
     *
     * @param string $mandrillId
     * @return Mail
     */
    public function setMandrillId($mandrillId)
    {
        $this->mandrillId = $mandrillId;

        return $this;
    }

    /**
     * Get mandrillId
     *
     * @return string 
     */
    public function getMandrillId()
    {
        return $this->mandrillId;
    }

    /**
     * Set rejectReason
     *
     * @param string $rejectReason
     * @return Mail
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * Get rejectReason
     *
     * @return string 
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }
    /**
     * @var string
     */
    private $username;

    /**
     * @var integer
     */
    private $followerCount;


    /**
     * Set username
     *
     * @param string $username
     * @return Mail
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set followerCount
     *
     * @param integer $followerCount
     * @return Mail
     */
    public function setFollowerCount($followerCount)
    {
        $this->followerCount = $followerCount;

        return $this;
    }

    /**
     * Get followerCount
     *
     * @return integer 
     */
    public function getFollowerCount()
    {
        return $this->followerCount;
    }
    /**
     * @var string
     */
    private $mailVersion;


    /**
     * Set mailVersion
     *
     * @param string $mailVersion
     * @return Mail
     */
    public function setMailVersion($mailVersion)
    {
        $this->mailVersion = $mailVersion;

        return $this;
    }

    /**
     * Get mailVersion
     *
     * @return string 
     */
    public function getMailVersion()
    {
        return $this->mailVersion;
    }
    /**
     * @var string
     */
    private $uid;


    /**
     * Set uid
     *
     * @param string $uid
     * @return Mail
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }
    /**
     * @var boolean
     */
    private $isOpened;

    /**
     * @var boolean
     */
    private $linkClicked;


    /**
     * Set isOpened
     *
     * @param boolean $isOpened
     * @return Mail
     */
    public function setIsOpened($isOpened)
    {
        $this->isOpened = $isOpened;

        return $this;
    }

    /**
     * Get isOpened
     *
     * @return boolean 
     */
    public function getIsOpened()
    {
        return $this->isOpened;
    }

    /**
     * Set linkClicked
     *
     * @param boolean $linkClicked
     * @return Mail
     */
    public function setLinkClicked($linkClicked)
    {
        $this->linkClicked = $linkClicked;

        return $this;
    }

    /**
     * Get linkClicked
     *
     * @return boolean 
     */
    public function getLinkClicked()
    {
        return $this->linkClicked;
    }
    /**
     * @var boolean
     */
    private $subscribed;


    /**
     * Set subscribed
     *
     * @param boolean $subscribed
     * @return Mail
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    /**
     * Get subscribed
     *
     * @return boolean 
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
    /**
     * @var \DateTime
     */
    private $sentAt;


    /**
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return Mail
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime 
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }
    /**
     * @var string
     */
    private $ipAdress;


    /**
     * Set ipAdress
     *
     * @param string $ipAdress
     * @return Mail
     */
    public function setIpAdress($ipAdress)
    {
        $this->ipAdress = $ipAdress;

        return $this;
    }

    /**
     * Get ipAdress
     *
     * @return string 
     */
    public function getIpAdress()
    {
        return $this->ipAdress;
    }
}
