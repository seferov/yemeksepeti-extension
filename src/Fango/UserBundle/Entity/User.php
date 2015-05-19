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
}
