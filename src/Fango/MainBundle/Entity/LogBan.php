<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogBan
 */
class LogBan
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LogBan
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @var \Fango\UserBundle\Entity\User
     */
    private $admin;

    /**
     * @var \Fango\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set admin
     *
     * @param \Fango\UserBundle\Entity\User $admin
     * @return LogBan
     */
    public function setAdmin(\Fango\UserBundle\Entity\User $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \Fango\UserBundle\Entity\User 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set user
     *
     * @param \Fango\UserBundle\Entity\User $user
     * @return LogBan
     */
    public function setUser(\Fango\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Fango\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
