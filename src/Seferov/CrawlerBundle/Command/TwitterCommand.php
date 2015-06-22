<?php

namespace Seferov\CrawlerBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use Doctrine\DBAL\DBALException;
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
        ], null, 5);

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

            $em->persist($twitter, $q);
            $em->flush();
            $em->clear();

            // Add followings to queue
            $friends = $connection->get('friends/ids', ['user_id' => $q->getTwitterId()]);

            $output->writeln('Fetching user friends...');

            foreach ($friends->ids as $key => $id) {
                $queue = new TwitterQueue();
                $queue->setIsCrawled(0);
                $queue->setTwitterId($id);
                $queue->setFollowerId($user->id);
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

            // Done
            $output->writeln('Done');
        }
    }
}