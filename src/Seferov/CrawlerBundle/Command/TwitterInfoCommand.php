<?php

namespace Seferov\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TwitterInfoCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\CrawlerBundle\Command
 */
class TwitterInfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seferov:crawler:twitter-info')
            ->setDescription('Twitter extra info')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $users = $em->getRepository('SeferovCrawlerBundle:Twitter')->findBy([
            'followerCount' => null
        ], null, 100);

        foreach ($users as $user) {
            $data = json_decode($user->getInfo());
            $user->setFollowerCount($data->followers_count);
            $user->setFriendsCount($data->friends_count);
            $user->setWebsite($data->url);
            $user->setLang($data->lang);

            $output->writeln($user->getScreenName());
            $em->persist($user);
            $em->flush();
        }

        $output->writeln('<comment>Done!</comment>');
    }
}