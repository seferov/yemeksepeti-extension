<?php

namespace Fango\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TotalReach
 */
class TotalReach
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $twitter;

    /**
     * @var integer
     */
    private $facebook;

    /**
     * @var integer
     */
    private $instagram;

    /**
     * @var integer
     */
    private $total;

    /**
     * @var \DateTime
     */
    private $date;


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
     * Set twitter
     *
     * @param integer $twitter
     * @return TotalReach
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return integer 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param integer $facebook
     * @return TotalReach
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return integer 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param integer $instagram
     * @return TotalReach
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return integer 
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return TotalReach
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return TotalReach
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
