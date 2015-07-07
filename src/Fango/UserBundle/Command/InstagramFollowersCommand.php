<?php

namespace Fango\UserBundle\Command;

use MetzWeb\Instagram\Instagram;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReachCalculatorCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\UserBundle\Command
 */
class InstagramFollowersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:user:instagram-followers-filler')
            ->setDescription('Fills instagram follower counts where it is not recorded')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $instagramNetworks = $em->getRepository('FangoUserBundle:Network')->findBy([
            'type' => 'instagram',
            'followersCount' => null
        ]);

        $instagram = new Instagram($this->getContainer()->getParameter('instagram_client_id'));

        foreach ($instagramNetworks as $network) {
            $user = $instagram->getUser($network->getNetworkId());
            $output->writeln($network->getDisplay());

            if (!isset($user->data)) {
                var_dump($user);
                $network->setFollowersCount(0);
            }
            else {
                $output->writeln($user->data->counts->followed_by);
                $network->setFollowersCount($user->data->counts->followed_by);
            }

            $em->persist($network);
            $em->flush();
        }
    }
}