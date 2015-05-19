<?php

namespace Fango\MainBundle\Entity;

/**
 * UserCampaign
 */
class UserCampaign
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $uniqueLink;


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
     * Set uniqueLink
     *
     * @param string $uniqueLink
     * @return UserCampaign
     */
    public function setUniqueLink($uniqueLink)
    {
        $this->uniqueLink = $uniqueLink;

        return $this;
    }

    /**
     * Get uniqueLink
     *
     * @return string 
     */
    public function getUniqueLink()
    {
        return $this->uniqueLink;
    }
    /**
     * @var \Fango\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Fango\MainBundle\Entity\Campaign
     */
    private $campaign;


    /**
     * Set user
     *
     * @param \Fango\UserBundle\Entity\User $user
     * @return UserCampaign
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

    /**
     * Set campaign
     *
     * @param \Fango\MainBundle\Entity\Campaign $campaign
     * @return UserCampaign
     */
    public function setCampaign(\Fango\MainBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \Fango\MainBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }
}
