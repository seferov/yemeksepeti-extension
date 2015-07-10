<?php

namespace Seferov\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Twitter
 */
class GoTwitter
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
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $info;


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
     * @return Twitter
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
     * Set email
     *
     * @param string $email
     * @return Twitter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Twitter
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
     * Set info
     *
     * @param string $info
     * @return Twitter
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }
    /**
     * @var string
     */
    private $screenName;

    /**
     * @var string
     */
    private $fullname;


    /**
     * Set screenName
     *
     * @param string $screenName
     * @return Twitter
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string 
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Twitter
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
    private $followerCount;

    /**
     * @var integer
     */
    private $friendsCount;

    /**
     * @var string
     */
    private $website;


    /**
     * Set followerCount
     *
     * @param integer $followerCount
     * @return Twitter
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
     * Set friendsCount
     *
     * @param integer $friendsCount
     * @return Twitter
     */
    public function setFriendsCount($friendsCount)
    {
        $this->friendsCount = $friendsCount;

        return $this;
    }

    /**
     * Get friendsCount
     *
     * @return integer 
     */
    public function getFriendsCount()
    {
        return $this->friendsCount;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Twitter
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }
    /**
     * @var string
     */
    private $lang;


    /**
     * Set lang
     *
     * @param string $lang
     * @return Twitter
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
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $favouritesCount;

    /**
     * @var integer
     */
    private $listedCount;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $profileImageUrlHttps;

    /**
     * @var integer
     */
    private $statusesCount;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var boolean
     */
    private $verified;

    /**
     * @var integer
     */
    private $utcOffset;


    /**
     * Set description
     *
     * @param string $description
     * @return GoTwitter
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
     * Set favouritesCount
     *
     * @param integer $favouritesCount
     * @return GoTwitter
     */
    public function setFavouritesCount($favouritesCount)
    {
        $this->favouritesCount = $favouritesCount;

        return $this;
    }

    /**
     * Get favouritesCount
     *
     * @return integer 
     */
    public function getFavouritesCount()
    {
        return $this->favouritesCount;
    }

    /**
     * Set listedCount
     *
     * @param integer $listedCount
     * @return GoTwitter
     */
    public function setListedCount($listedCount)
    {
        $this->listedCount = $listedCount;

        return $this;
    }

    /**
     * Get listedCount
     *
     * @return integer 
     */
    public function getListedCount()
    {
        return $this->listedCount;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return GoTwitter
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
     * Set profileImageUrlHttps
     *
     * @param string $profileImageUrlHttps
     * @return GoTwitter
     */
    public function setProfileImageUrlHttps($profileImageUrlHttps)
    {
        $this->profileImageUrlHttps = $profileImageUrlHttps;

        return $this;
    }

    /**
     * Get profileImageUrlHttps
     *
     * @return string 
     */
    public function getProfileImageUrlHttps()
    {
        return $this->profileImageUrlHttps;
    }

    /**
     * Set statusesCount
     *
     * @param integer $statusesCount
     * @return GoTwitter
     */
    public function setStatusesCount($statusesCount)
    {
        $this->statusesCount = $statusesCount;

        return $this;
    }

    /**
     * Get statusesCount
     *
     * @return integer 
     */
    public function getStatusesCount()
    {
        return $this->statusesCount;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return GoTwitter
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     * @return GoTwitter
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set utcOffset
     *
     * @param integer $utcOffset
     * @return GoTwitter
     */
    public function setUtcOffset($utcOffset)
    {
        $this->utcOffset = $utcOffset;

        return $this;
    }

    /**
     * Get utcOffset
     *
     * @return integer 
     */
    public function getUtcOffset()
    {
        return $this->utcOffset;
    }
}
