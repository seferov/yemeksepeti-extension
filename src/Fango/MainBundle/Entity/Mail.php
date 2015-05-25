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
}
