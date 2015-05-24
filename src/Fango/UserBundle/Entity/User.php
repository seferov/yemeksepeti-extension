<?php

namespace Fango\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

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
     * @var string
     */
    private $fullname;


    /**
     * Set fullname
     *
     * @param string $fullname
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $userCampaigns;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->userCampaigns = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userCampaigns
     *
     * @param \Fango\MainBundle\Entity\UserCampaign $userCampaigns
     * @return User
     */
    public function addUserCampaign(\Fango\MainBundle\Entity\UserCampaign $userCampaigns)
    {
        $this->userCampaigns[] = $userCampaigns;

        return $this;
    }

    /**
     * Remove userCampaigns
     *
     * @param \Fango\MainBundle\Entity\UserCampaign $userCampaigns
     */
    public function removeUserCampaign(\Fango\MainBundle\Entity\UserCampaign $userCampaigns)
    {
        $this->userCampaigns->removeElement($userCampaigns);
    }

    /**
     * Get userCampaigns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserCampaigns()
    {
        return $this->userCampaigns;
    }

    /**
     * @var float
     */
    private $earnings;

    /**
     * @var float
     */
    private $paid;

    /**
     * Set earnings
     *
     * @param float $earnings
     * @return User
     */
    public function setEarnings($earnings)
    {
        $this->earnings = $earnings;

        return $this;
    }

    /**
     * Get earnings
     *
     * @return float 
     */
    public function getEarnings()
    {
        return $this->earnings;
    }

    /**
     * Set paid
     *
     * @param float $paid
     * @return User
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return float 
     */
    public function getPaid()
    {
        return $this->paid;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $networks;


    /**
     * Add networks
     *
     * @param \Fango\UserBundle\Entity\Network $networks
     * @return User
     */
    public function addNetwork(\Fango\UserBundle\Entity\Network $networks)
    {
        $this->networks[] = $networks;

        return $this;
    }

    /**
     * Remove networks
     *
     * @param \Fango\UserBundle\Entity\Network $networks
     */
    public function removeNetwork(\Fango\UserBundle\Entity\Network $networks)
    {
        $this->networks->removeElement($networks);
    }

    /**
     * Get networks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNetworks()
    {
        return $this->networks;
    }
    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
}
