<?php

namespace Seferov\CrawlerBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
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
        $connection = new TwitterOAuth($this->getContainer()->getParameter('twitter_client'), $this->getContainer()->getParameter('twitter_secret'));

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $queue = $em->getRepository('SeferovCrawlerBundle:TwitterQueue')->findBy([
            'isCrawled' => false
        ], null, 10);

        foreach ($queue as $q) {
            $user = $connection->get('users/show', ['user_id' => $q->getTwitterId()]);

            if (property_exists($user, 'errors') && count($user->errors)) {
                continue;
            }

            $email = $this->getContainer()->get('seferov_email_extractor.extractor')->process($user->description);
            $email = count($email) ? $email[0] : null;

            $twitter = new Twitter();
            $twitter->setTwitterId($user->id);
            $twitter->setEmail($email);
            $twitter->setScreenName($user->screen_name);
            $twitter->setFullname($user->name);
            $twitter->setInfo(json_encode($user));
            $twitter->setCreatedAt(new \DateTime('now'));

            $q->setIsCrawled(true);

            $em->persist($q);
            $em->persist($twitter);
            $em->flush();

            $redis = $this->getContainer()->get('snc_redis.crawler');
            $redis->rpush('twitter_queue', $q->getTwitterId());

            // Done
            $output->writeln('Done: ' . $user->screen_name);
        }
    }
}