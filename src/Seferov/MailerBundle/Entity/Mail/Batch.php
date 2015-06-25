<?php

namespace Seferov\MailerBundle\Entity\Mail;

use Doctrine\ORM\Mapping as ORM;

/**
 * Batch
 */
class Batch
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $uid;

    /**
     * @var boolean
     */
    private $isOpened = false;

    /**
     * @var boolean
     */
    private $linkClicked = false;

    /**
     * @var boolean
     */
    private $complaint = false;

    /**
     * @var boolean
     */
    private $bounce = false;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var \DateTime
     */
    private $sentAt;

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
     * Set version
     *
     * @param string $version
     * @return Batch
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Batch
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set isOpened
     *
     * @param boolean $isOpened
     * @return Batch
     */
    public function setIsOpened($isOpened)
    {
        $this->isOpened = $isOpened;

        return $this;
    }

    /**
     * Get isOpened
     *
     * @return boolean 
     */
    public function getIsOpened()
    {
        return $this->isOpened;
    }

    /**
     * Set linkClicked
     *
     * @param boolean $linkClicked
     * @return Batch
     */
    public function setLinkClicked($linkClicked)
    {
        $this->linkClicked = $linkClicked;

        return $this;
    }

    /**
     * Get linkClicked
     *
     * @return boolean 
     */
    public function getLinkClicked()
    {
        return $this->linkClicked;
    }

    /**
     * Set complaint
     *
     * @param boolean $complaint
     * @return Batch
     */
    public function setComplaint($complaint)
    {
        $this->complaint = $complaint;

        return $this;
    }

    /**
     * Get complaint
     *
     * @return boolean 
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * Set bounce
     *
     * @param boolean $bounce
     * @return Batch
     */
    public function setBounce($bounce)
    {
        $this->bounce = $bounce;

        return $this;
    }

    /**
     * Get bounce
     *
     * @return boolean 
     */
    public function getBounce()
    {
        return $this->bounce;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return Batch
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
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return Batch
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime 
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }
    /**
     * @var \Seferov\MailerBundle\Entity\Mail
     */
    private $mail;


    /**
     * Set mail
     *
     * @param \Seferov\MailerBundle\Entity\Mail $mail
     * @return Batch
     */
    public function setMail(\Seferov\MailerBundle\Entity\Mail $mail = null)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return \Seferov\MailerBundle\Entity\Mail 
     */
    public function getMail()
    {
        return $this->mail;
    }
}
