<?php

namespace Fango\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class NetworkRepository
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Entity
 */
class NetworkRepository extends EntityRepository
{
    public function findRandomToken($type = 'twitter')
    {
        $em = $this->getEntityManager();
        $count = $em->createQuery('
            SELECT COUNT(n.id) FROM FangoUserBundle:Network n
            WHERE n.token IS NOT NULL
            AND n.type = :type
        ')
        ->setParameter('type', $type)
        ->getSingleScalarResult();

        $qb = $em->createQuery('
            SELECT DISTINCT n
            FROM FangoUserBundle:Network n
            WHERE n.token IS NOT NULL
            AND n.type = :type
        ')
        ->setParameter('type', $type)
        ->setMaxResults(1)
        ->setFirstResult(max(0, rand(0, $count)));

        return $qb->getResult();
    }
}
