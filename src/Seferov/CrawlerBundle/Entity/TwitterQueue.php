<?php

namespace Seferov\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TwitterQueue
 */
class TwitterQueue
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $twitterId;

    /**
     * @var boolean
     */
    private $isCrawled;


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
     * Set twitterId
     *
     * @param integer $twitterId
     * @return TwitterQueue
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return integer 
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set isCrawled
     *
     * @param boolean $isCrawled
     * @return TwitterQueue
     */
    public function setIsCrawled($isCrawled)
    {
        $this->isCrawled = $isCrawled;

        return $this;
    }

    /**
     * Get isCrawled
     *
     * @return boolean 
     */
    public function getIsCrawled()
    {
        return $this->isCrawled;
    }
    /**
     * @var integer
     */
    private $followerId;


    /**
     * Set followerId
     *
     * @param integer $followerId
     * @return TwitterQueue
     */
    public function setFollowerId($followerId)
    {
        $this->followerId = $followerId;

        return $this;
    }

    /**
     * Get followerId
     *
     * @return integer 
     */
    public function getFollowerId()
    {
        return $this->followerId;
    }
    /**
     * @var boolean
     */
    private $hasError;


    /**
     * Set hasError
     *
     * @param boolean $hasError
     * @return TwitterQueue
     */
    public function setHasError($hasError)
    {
        $this->hasError = $hasError;

        return $this;
    }

    /**
     * Get hasError
     *
     * @return boolean 
     */
    public function getHasError()
    {
        return $this->hasError;
    }
}
