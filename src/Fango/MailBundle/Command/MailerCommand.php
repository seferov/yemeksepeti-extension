<?php

namespace Fango\MailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailerCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MailBundle\Command
 */
class MailerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:mailer:send')
            ->addArgument(
                'batch',
                InputArgument::REQUIRED
            )
            ->setDescription('Fango mailer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var \Fango\MainBundle\Entity\Mail[] $mails */
        $mails = $em->getRepository('FangoMainBundle:Mail')->getMails(34);
        $mailer = $this->getContainer()->get('mailer');
        $templating = $this->getContainer()->get('templating');

        switch ($input->getArgument('batch')) {
            case 1:
                $versions = [
                    'igifgpcj2' => ['subject' => 'Paid campaign for %s'],
                    'igifgbij2' => ['subject' => 'Business inquiry for %s']
                ];
                $template = '@FangoMail/invitation.html.twig';
                break;
            case 2:
            default:
                $versions = [
                    'igifgtcj' => ['subject' => 'Twitter campaign for %s'],
                    'igifgfcj' => ['subject' => 'Facebook campaign for %s']
                ];
                $template = '@FangoMail/invitation2.html.twig';
                break;
        }

        foreach ($mails as $mail) {
            $version = array_rand($versions);
            $uid = md5(uniqid(mt_rand(), true));

            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf($versions[$version]['subject'], $mail->getUsername()))
                ->setFrom(['jessica@fango.me' => 'Jessica Taylor'])
                ->setTo($mail->getEmail())
                ->setBody($templating->render($template, [
                    'version' => $version,
                    'uid' => $uid
                ]), 'text/html');

            $mailer->send($message);

            $mail->setStatus('sent');
            $mail->setMailVersion($version);
            $mail->setUid($uid);

            if ($input->getArgument('batch') == 1) {
                $mail->setSentAt(new \DateTime('now'));
            }
            else {
                $mail->setSecondSentAt(new \DateTime('now'));
            }

            $em->persist($mail);
            $em->flush();
            sleep(1);
        }

        $output->writeLn('Done!');
    }
}