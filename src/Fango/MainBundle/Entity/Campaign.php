<?php

namespace Fango\MainBundle\Entity;

/**
 * Campaign
 */
class Campaign
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $logo;


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
     * @return Campaign
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
     * Set logo
     *
     * @param string $logo
     * @return Campaign
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
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
     * @return Campaign
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
     * @var string
     */
    private $actionLink;

    /**
     * @var float
     */
    private $cpa;


    /**
     * Set actionLink
     *
     * @param string $actionLink
     * @return Campaign
     */
    public function setActionLink($actionLink)
    {
        $this->actionLink = $actionLink;

        return $this;
    }

    /**
     * Get actionLink
     *
     * @return string 
     */
    public function getActionLink()
    {
        return $this->actionLink;
    }

    /**
     * Set cpa
     *
     * @param float $cpa
     * @return Campaign
     */
    public function setCpa($cpa)
    {
        $this->cpa = $cpa;

        return $this;
    }

    /**
     * Get cpa
     *
     * @return float 
     */
    public function getCpa()
    {
        return $this->cpa;
    }
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     * @return Campaign
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $countries;


    /**
     * Add countries
     *
     * @param \Fango\MainBundle\Entity\CampaignCountry $countries
     * @return Campaign
     */
    public function addCountry(\Fango\MainBundle\Entity\CampaignCountry $countries)
    {
        $this->countries[] = $countries;

        return $this;
    }

    /**
     * Remove countries
     *
     * @param \Fango\MainBundle\Entity\CampaignCountry $countries
     */
    public function removeCountry(\Fango\MainBundle\Entity\CampaignCountry $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }
    /**
     * @var string
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     * @return Campaign
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
     * @var string
     */
    private $previewLink;


    /**
     * Set previewLink
     *
     * @param string $previewLink
     * @return Campaign
     */
    public function setPreviewLink($previewLink)
    {
        $this->previewLink = $previewLink;

        return $this;
    }

    /**
     * Get previewLink
     *
     * @return string 
     */
    public function getPreviewLink()
    {
        return $this->previewLink;
    }
    /**
     * @var string
     */
    private $socialText;


    /**
     * Set socialText
     *
     * @param string $socialText
     * @return Campaign
     */
    public function setSocialText($socialText)
    {
        $this->socialText = $socialText;

        return $this;
    }

    /**
     * Get socialText
     *
     * @return string 
     */
    public function getSocialText()
    {
        return $this->socialText;
    }
    /**
     * @var integer
     */
    private $userId;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return Campaign
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
