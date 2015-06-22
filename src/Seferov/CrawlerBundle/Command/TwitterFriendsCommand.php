<?php

namespace Seferov\CrawlerBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use Doctrine\DBAL\DBALException;
use Seferov\CrawlerBundle\Entity\TwitterQueue;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TwitterFriendsCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\CrawlerBundle\Command
 */
class TwitterFriendsCommand  extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seferov:crawler:twitter-friends')
            ->setDescription('Crawls twitter user friends')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new TwitterOAuth($this->getContainer()->getParameter('twitter_client'), $this->getContainer()->getParameter('twitter_secret'));
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $redis = $this->getContainer()->get('snc_redis.crawler');
        $userId = $redis->lpop('twitter_queue');

        // Add followings to queue
        $friends = $connection->get('friends/ids', ['user_id' => $userId]);

        $output->writeln('Fetching user friends...');

        foreach ($friends->ids as $key => $id) {
            $queue = new TwitterQueue();
            $queue->setIsCrawled(0);
            $queue->setTwitterId($id);
            $queue->setFollowerId($userId);
            $em->persist($queue);
            $output->writeln($id);

            try {
                $em->flush();
            }
            catch (DBALException $e) {
                // probably duplicate content

                if (!$em->isOpen()) {
                    $em = $em->create($em->getConnection(), $em->getConfiguration());
                }
            }

            if ($key  % 5 == 0) {
                $em->clear();
            }
        }

        $em->flush();
    }
}