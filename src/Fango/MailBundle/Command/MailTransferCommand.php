<?php

namespace Fango\MailBundle\Command;

use Seferov\MailerBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailTransferCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MailBundle\Command
 */
class MailTransferCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fango:mailer:transfer')
            ->setDescription('Fango mailer transfer');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        for ($i = 1; $i <= 100; $i++) {
            $mail = $em
                ->getRepository('FangoMainBundle:Mail')
                ->findOneBy([
                    'status' => 'sent',
                ]);

            $email = $em->getRepository('SeferovMailerBundle:Mail')->findOneBy([
                'mail' => $mail->getEmail()
            ]);

            $newMail = false;
            if (!$email instanceof Mail) {
                $email = new Mail();
                $newMail = true;
            }

            $email->setProblem($mail->getComplaint() || $mail->getBounce());
            $email->setUnsubscribed(!$mail->getSubscribed());
            $email->setFollowerCount($mail->getFollowerCount());
            $email->setSource('instagram');
            $email->setUsername($mail->getUsername());
            $email->setMail($mail->getEmail());
            $email->setLastBatch(1);

            $mail->setStatus('transferred');

            $em->persist($email, $mail);
            $em->flush();

            $out = $i. '. ' .$email->getMail();
            $out .= $newMail ? ' <comment>new</comment>' : ' <question>existing</question>';

            $output->writeln($out);
        }
    }
}
