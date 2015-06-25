<?php

namespace Seferov\CrawlerBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use Doctrine\DBAL\LockMode;
use Seferov\CrawlerBundle\Entity\Twitter;
use Seferov\CrawlerBundle\Entity\TwitterQueue;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TwitterCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\CrawlerBundle\Command
 */
class TwitterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seferov:crawler:twitter')
            ->setDescription('Crawls twitter users')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $redis = $this->getContainer()->get('snc_redis.crawler');

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        if (!$redis->exists('token')) {
            $this->getAccessToken();
        }

        $connection = new TwitterOAuth($this->getContainer()->getParameter('twitter_client'), $this->getContainer()->getParameter('twitter_secret'), $redis->get('token'), $redis->get('token_secret'));

        for ($i = 0; $i < 100; $i++) {
            $em->getConnection()->beginTransaction();
            $queue = $em->createQueryBuilder()
                ->select('q')
                ->from('SeferovCrawlerBundle:TwitterQueue', 'q')
                ->where('q.isCrawled = :isCrawled')
                ->andWhere('q.hasError is null or q.hasError = :hasError')
                ->setParameters([
                    'isCrawled' => false,
                    'hasError' => false
                ])
                ->setMaxResults(1)
                ->getQuery()
                ->setLockMode(LockMode::PESSIMISTIC_WRITE)
                ->getSingleResult();

            $id = $queue->getTwitterId();

            $output->writeln(sprintf('Getting %s...', $id));
            $user = $connection->get('users/show', ['user_id' => $id]);

            // Check rate limit
            $xHeader = $connection->getLastXHeaders();
            if ($xHeader['x_rate_limit_remaining'] < 1) {
                $output->writeln('<info>Getting new access token...</info>');

                // Get new random access token
                $this->getAccessToken();
                $em->getConnection()->commit();
                $this->execute($input, $output);
            }

            // Error handling
            if (property_exists($user, 'errors') && count($user->errors)) {
                $queue->setHasError(true);
                $em->persist($queue);
                $em->flush();
                $em->getConnection()->commit();
                $output->writeln('Aborted');
                continue;
            }

            $email = $this->getContainer()->get('seferov_extractor.email_extractor')->process($user->description);
            $email = count($email) ? $email[0] : null;

            $twitter = new Twitter();
            $twitter->setTwitterId($user->id);
            $twitter->setEmail($email);
            $twitter->setScreenName($user->screen_name);
            $twitter->setFullname($user->name);
            $twitter->setLang($user->lang);
            $twitter->setFriendsCount($user->friends_count);
            $twitter->setFollowerCount($user->followers_count);
            $twitter->setWebsite($user->url);
            $twitter->setInfo(json_encode($user));
            $twitter->setCreatedAt(new \DateTime('now'));

            $queue->setIsCrawled(true);

            $em->persist($queue);
            $em->persist($twitter);
            $em->flush();

            $em->getConnection()->commit();

            $redis->rpush('twitter_queue', $id);

            // Done
            $output->writeln('Done: ' . $user->screen_name);
        }
    }

    private function getAccessToken()
    {
        $redis = $this->getContainer()->get('snc_redis.crawler');

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $networks = $em->getRepository('FangoUserBundle:Network')->findRandomToken();

        foreach ($networks as $network) {
            $redis->set('token', $network->getToken());
            $redis->set('token_secret', $network->getTokenSecret());
        }
    }
}