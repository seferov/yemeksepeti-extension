<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 */
class Transaction
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var string
     */
    private $referer;

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
     * Set hash
     *
     * @param string $hash
     * @return Transaction
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return Transaction
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set referer
     *
     * @param string $referer
     * @return Transaction
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Transaction
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
     * @var \Fango\MainBundle\Entity\UserCampaign
     */
    private $userCampaign;


    /**
     * Set userCampaign
     *
     * @param \Fango\MainBundle\Entity\UserCampaign $userCampaign
     * @return Transaction
     */
    public function setUserCampaign(\Fango\MainBundle\Entity\UserCampaign $userCampaign = null)
    {
        $this->userCampaign = $userCampaign;

        return $this;
    }

    /**
     * Get userCampaign
     *
     * @return \Fango\MainBundle\Entity\UserCampaign
     */
    public function getUserCampaign()
    {
        return $this->userCampaign;
    }
    /**
     * @var boolean
     */
    private $action;


    /**
     * Set action
     *
     * @param boolean $action
     * @return Transaction
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return boolean
     */
    public function getAction()
    {
        return $this->action;
    }
}
