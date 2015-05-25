<?php

namespace Fango\MainBundle\Command;

use Hip\MandrillBundle\Message;
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
        $dispatcher = $this->getContainer()->get('hip_mandrill.dispatcher');
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

//        $mails = $em
//            ->getRepository('FangoMainBundle:Mail')
//            ->findBy([
//                'status' => 'raw'
//            ], ['followerCount' => 'ASC'], 1000);
//
//        foreach ($mails as $mail) {
//            $h = preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $mail->getActiveHour(), $matches);
//            $mail->setActiveHour($matches[4]);
//            $mail->setStatus('new');
//            $em->persist($mail);
//            $em->flush();
//        }
//        exit;


        $mails = $em
            ->getRepository('FangoMainBundle:Mail')
            ->getMails();

        $versions = [
            ['subject' => 'Business inquiry for %s', 'html' => $this->getContainer()->get('templating')->render('@FangoMain/Email/invitation-a.html.twig')],
            ['subject' => 'Sponsored post for %s', 'html' => $this->getContainer()->get('templating')->render('@FangoMain/Email/invitation-b.html.twig')]
        ];

        foreach ($mails as $mail) {
            $message = new Message();
            $version = $versions[array_rand($versions)];
            $message
                ->setFromEmail('invitation@fango.me')
                ->setFromName('Fango')
                ->addTo($mail->getEmail())
                ->setSubject(sprintf($version['subject'], $mail->getUsername()))
                ->setHtml($version['html'])
            ;

            $result = $dispatcher->send($message);

            $mail->setStatus($result[0]['status']);
            $mail->setMandrillId($result[0]['_id']);
            $mail->setRejectReason($result[0]['reject_reason']);

            $em->persist($mail);
            $em->flush();
        }

        $output->writeLn('Done!');
    }
}