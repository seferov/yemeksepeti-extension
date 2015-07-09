<?php

namespace Fango\MainBundle\Entity\Campaign;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuggestedImages
 */
class SuggestedImages
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $link;


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
     * Set link
     *
     * @param string $link
     * @return SuggestedImages
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * @var \Fango\MainBundle\Entity\Campaign
     */
    private $campaign;


    /**
     * Set campaign
     *
     * @param \Fango\MainBundle\Entity\Campaign $campaign
     * @return SuggestedImages
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
    /**
     * @var string
     */
    private $thumb;


    /**
     * Set thumb
     *
     * @param string $thumb
     * @return SuggestedImages
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return string 
     */
    public function getThumb()
    {
        return $this->thumb;
    }
}
