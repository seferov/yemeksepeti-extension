<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignCountry
 */
class CampaignCountry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $country;


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
     * Set country
     *
     * @param string $country
     * @return CampaignCountry
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
    /**
     * @var \Fango\MainBundle\Entity\Campaign
     */
    private $campaign;


    /**
     * Set campaign
     *
     * @param \Fango\MainBundle\Entity\Campaign $campaign
     * @return CampaignCountry
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
