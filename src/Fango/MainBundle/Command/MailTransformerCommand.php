<?php

namespace Fango\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailTransformer
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Command
 */
class MailTransformerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:mailer:transform')
            ->setDescription('Fango mailer transformer (Active hours)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $mails = $em
            ->getRepository('FangoMainBundle:Mail')
            ->findBy([
                'status' => 'raw',
                'activeHour' => date('H')
            ], ['followerCount' => 'ASC'], 200);

        foreach ($mails as $mail) {
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $mail->getActiveHour(), $matches);
            $mail->setActiveHour($matches[4]);
            $mail->setStatus('new');
            $em->persist($mail);
            $em->flush();
            $output->writeln($mail->getEmail());
        }
    }
}
