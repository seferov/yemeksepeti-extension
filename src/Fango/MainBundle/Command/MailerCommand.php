<?php

namespace Fango\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailerCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MainBundle\Command
 */
class MailerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:mailer:send')
            ->setDescription('Fango mailer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var \Fango\MainBundle\Entity\Mail[] $mails */
        $mails = $em->getRepository('FangoMainBundle:Mail')->getMails(30);
        $mailer = $this->getContainer()->get('mailer');
        $templating = $this->getContainer()->get('templating');

        $versions = [
            'igifgpcj' => ['subject' => 'Paid campaign for %s'],
            'igifgbij' => ['subject' => 'Business inquiry for %s']
        ];

        foreach ($mails as $mail) {
            $version = array_rand($versions);
            $uid = md5(uniqid(mt_rand(), true));

            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf($versions[$version]['subject'], $mail->getUsername()))
                ->setFrom(['jessica@fango.me' => 'Jessica Taylor'])
                ->setTo($mail->getEmail())
                ->setBody($templating->render('@FangoMain/Email/invitation.html.twig', [
                    'version' => $version,
                    'uid' => $uid
                ]), 'text/html');

            $mailer->send($message);

            $mail->setStatus('sent');
            $mail->setMailVersion($version);
            $mail->setUid($uid);
            $mail->setSentAt(new \DateTime('now'));

            $em->persist($mail);
            $em->flush();
            sleep(1);
        }

        $output->writeLn('Done!');
    }
}