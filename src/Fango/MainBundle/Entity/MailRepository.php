<?php

namespace Fango\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class MailRepository
 * @package Fango\MainBundle\Entity
 */
class MailRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getMails()
    {
        return $this->createQueryBuilder('m')
            ->where('m.status = :status')
            ->andWhere('m.activeHour = :activeHour')
            ->setParameters([
                'status' => 'new',
                'activeHour' => date('H')
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
