<?php

namespace Fango\UserBundle\Command;

use Doctrine\ORM\AbstractQuery;
use Fango\UserBundle\Entity\TotalReach;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReachCalculatorCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Command
 */
class ReachCalculatorCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fango:calculator:total-reach')
            ->setDescription('Calculates total reach of users')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $date = new \DateTime('now');

        $record = $em->getRepository('FangoUserBundle:TotalReach')->findOneBy([
            'date' => $date
        ]);

        if ($record) {
            $output->writeln('<comment>Already calculated!</comment>');
            exit;
        }

        $networks = $em
            ->getRepository('FangoUserBundle:Network')
            ->createQueryBuilder('n')
            ->select('n.type', 'n.followersCount')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY)
        ;

        $totalFacebook = 0;
        $totalTwitter = 0;
        $totalInstagram = 0;

        foreach ($networks as $network) {
            switch ($network['type']) {
                case 'facebook':
                    $totalFacebook+=$network['followersCount'];
                    break;
                case 'twitter':
                    $totalTwitter+=$network['followersCount'];
                    break;
                case 'instagram':
                    $totalInstagram+=$network['followersCount'];
                    break;
            }
        }

        $newRecord = new TotalReach();
        $newRecord->setFacebook($totalFacebook);
        $newRecord->setTwitter($totalTwitter);
        $newRecord->setInstagram($totalInstagram);
        $newRecord->setDate($date);

        $em->persist($newRecord);
        $em->flush();
    }
}