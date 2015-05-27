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
            ->setName('fango:mailer')
            ->setDescription('Fango mailer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
//
//        $mails = $em
//            ->getRepository('FangoMainBundle:Mail')
//            ->findBy([
//                'status' => 'raw'
//            ], ['followerCount' => 'ASC'], 1000);
//
//        foreach ($mails as $mail) {
//            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $mail->getActiveHour(), $matches);
//            $mail->setActiveHour($matches[4]);
//            $mail->setStatus('new');
//            $em->persist($mail);
//            $em->flush();
//            $output->writeln($mail->getEmail());
//        }
//        exit;

        /** @var \Fango\MainBundle\Entity\Mail[] $mails */
        $mails = $em->getRepository('FangoMainBundle:Mail')->findBy(['email' => 'farhad.safarov@gmail.com']);
        $mailer = $this->getContainer()->get('mailer');

        $versions = [
            'igifgbif' => ['subject' => 'Business inquiry for %s'],
            'igifgspf' => ['subject' => 'Sponsored post for %s']
        ];

        foreach ($mails as $mail) {
            $version = array_rand($versions);
            $uid = md5(uniqid(mt_rand(), true));

            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf($versions[$version]['subject'], $mail->getUsername()))
                ->setFrom(['invitation@fango.me' => 'Fango'])
                ->setTo($mail->getEmail())
                ->setBody($this->getContainer()->get('templating')->render('@FangoMain/Email/invitation.html.twig', [
                    'version' => $version,
                    'uid' => $uid
                ]), 'text/html');

            $mailer->send($message);

            $mail->setStatus('sent');
            $mail->setMailVersion($version);
            $mail->setUid($uid);

            $em->persist($mail);
            $em->flush();
        }

        $output->writeLn('Done!');
    }
}