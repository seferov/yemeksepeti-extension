<?php

namespace Fango\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Entity
 */
class UserRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param $network
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserBySocialId($id, $network)
    {
        return $this->createQueryBuilder('u')
            ->join('u.networks', 'n')
            ->where('n.type = :type')
            ->andWhere('n.networkId = :id')
            ->setParameters([
                'type' => $network,
                'id' => $id
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
