<?php

namespace Fango\UserBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReachCalculatorCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Command
 */
class TwitterFollowersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:user:twitter-followers-filler')
            ->setDescription('Fills twitters follower counts where it is not recorded')
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

        $twitterNetworks = $em->getRepository('FangoUserBundle:Network')->findBy([
            'type' => 'twitter',
            'followersCount' => null
        ]);

        $connection = new TwitterOAuth($this->getContainer()->getParameter('twitter_client'), $this->getContainer()->getParameter('twitter_secret'), $redis->get('token'), $redis->get('token_secret'));

        foreach ($twitterNetworks as $network) {
            $user = $connection->get('users/show', ['user_id' => $network->getNetworkId()]);

            // Check rate limit
            $xHeader = $connection->getLastXHeaders();
            if (!array_key_exists('x_rate_limit_remaining', $xHeader) || $xHeader['x_rate_limit_remaining'] < 1) {
                $output->writeln('<info>Getting new access token...</info>');

                // Get new random access token
                $this->getAccessToken();
                $this->execute($input, $output);
            }

            $output->write($network->getDisplay());
            $output->writeln($user->screen_name.': '.$user->followers_count);
            $network->setFollowersCount($user->followers_count);
            $em->persist($network);
            $em->flush();
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