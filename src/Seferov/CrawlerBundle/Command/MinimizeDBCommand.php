<?php

namespace Seferov\CrawlerBundle\Command;

use Doctrine\DBAL\LockMode;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MinimizeDBCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\CrawlerBundle\Command
 */
class MinimizeDBCommand  extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seferov:crawler:minimize-db')
            ->setDescription('Minimizes DB')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $em->getConnection()->beginTransaction();

        /** @var \Seferov\CrawlerBundle\Entity\GoTwitter[] $users */
        $users = $em->getRepository('SeferovCrawlerBundle:GoTwitter')
            ->createQueryBuilder('u')
            ->where('u.info is not null')
            ->setMaxResults(1000)
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getResult();

        foreach ($users as $user) {
            $info = json_decode($user->getInfo());
            $user->setDescription($info->description);
            $user->setFavouritesCount($info->favourites_count);
            $user->setListedCount($info->listed_count);
            $user->setLocation($info->location);
            $user->setProfileImageUrlHttps($info->profile_image_url_https);
            $user->setStatusesCount($info->statuses_count);
            $user->setTimezone($info->time_zone);
            $user->setVerified($info->verified);
            $user->setUtcOffset($info->utc_offset);
            $user->setInfo(null);

            $em->persist($user);
            $em->flush();

            $output->writeln($info->screen_name);
        }

        $em->getConnection()->commit();
    }
}