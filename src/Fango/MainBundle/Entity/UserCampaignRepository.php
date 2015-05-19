<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserCampaignRepository
 * @package Fango\MainBundle\Entity
 */
class UserCampaignRepository extends EntityRepository
{
    /**
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastLink()
    {
        $result = $this->createQueryBuilder('uc')
            ->select('uc.uniqueLink')
            ->orderBy('uc.id', 'desc')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (!$result) {
            return 'a';
        }

        return $result['uniqueLink'];
    }
}
