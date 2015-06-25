<?php

namespace Seferov\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mail
 */
class Mail
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var integer
     */
    private $followerCount;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $location;

    /**
     * @var boolean
     */
    private $problem =  false;

    /**
     * @var boolean
     */
    private $contacted = false;

    /**
     * @var boolean
     */
    private $unsubscribed = false;

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
     * Set mail
     *
     * @param string $mail
     * @return Mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set followerCount
     *
     * @param integer $followerCount
     * @return Mail
     */
    public function setFollowerCount($followerCount)
    {
        $this->followerCount = $followerCount;

        return $this;
    }

    /**
     * Get followerCount
     *
     * @return integer 
     */
    public function getFollowerCount()
    {
        return $this->followerCount;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Mail
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Mail
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }
    /**
     * @var string
     */
    private $source;


    /**
     * Set source
     *
     * @param string $source
     * @return Mail
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $fullname;


    /**
     * Set username
     *
     * @param string $username
     * @return Mail
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Mail
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
     * @var integer
     */
    private $lastBatch;


    /**
     * Set lastBatch
     *
     * @param integer $lastBatch
     * @return Mail
     */
    public function setLastBatch($lastBatch)
    {
        $this->lastBatch = $lastBatch;

        return $this;
    }

    /**
     * Get lastBatch
     *
     * @return integer 
     */
    public function getLastBatch()
    {
        return $this->lastBatch;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $batches;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->batches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add batches
     *
     * @param \Seferov\MailerBundle\Entity\Mail\Batch $batches
     * @return Mail
     */
    public function addBatch(\Seferov\MailerBundle\Entity\Mail\Batch $batches)
    {
        $this->batches[] = $batches;

        return $this;
    }

    /**
     * Remove batches
     *
     * @param \Seferov\MailerBundle\Entity\Mail\Batch $batches
     */
    public function removeBatch(\Seferov\MailerBundle\Entity\Mail\Batch $batches)
    {
        $this->batches->removeElement($batches);
    }

    /**
     * Get batches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBatches()
    {
        return $this->batches;
    }

    /**
     * Set unsubscribed
     *
     * @param boolean $unsubscribed
     * @return Mail
     */
    public function setUnsubscribed($unsubscribed)
    {
        $this->unsubscribed = $unsubscribed;

        return $this;
    }

    /**
     * Get unsubscribed
     *
     * @return boolean 
     */
    public function getUnsubscribed()
    {
        return $this->unsubscribed;
    }

    /**
     * Set problem
     *
     * @param boolean $problem
     * @return Mail
     */
    public function setProblem($problem)
    {
        $this->problem = $problem;

        return $this;
    }

    /**
     * Get problem
     *
     * @return boolean 
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * Set contacted
     *
     * @param boolean $contacted
     * @return Mail
     */
    public function setContacted($contacted)
    {
        $this->contacted = $contacted;

        return $this;
    }

    /**
     * Get contacted
     *
     * @return boolean 
     */
    public function getContacted()
    {
        return $this->contacted;
    }
}
