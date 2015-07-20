<?php

namespace Seferov\MailerBundle\Command;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\NoResultException;
use Seferov\MailerBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailerCommand
 * @author Farhad Safarov <http://ferhad.in>
 * @package Seferov\MailerBundle\Command
 */
class MailerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('seferov:mailer:send')
            ->addArgument(
                'batch',
                InputArgument::REQUIRED
            )
            ->setDescription('Batch mailer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $mailer = $this->getContainer()->get('mailer');
        $templating = $this->getContainer()->get('templating');
        $batchNumber = (int)$input->getArgument('batch');

        // Get random version
        switch ($batchNumber) {
            case 1:
                $query = $em->createQueryBuilder()
                    ->select('m')
                    ->from('SeferovMailerBundle:Mail', 'm')
                    ->where('m.lastBatch != :lastBatch or m.lastBatch is null')
                    ->andWhere('m.unsubscribed = false')
                    ->andWhere('m.lang = :lang')
                    ->andWhere('m.problem = false')
                    ->andWhere('m.contacted = false')
                    ->andWhere('m.source = :source')
                    ->setParameters([
                        'lastBatch' => $batchNumber,
                        'source' => 'twitter',
                        'lang' => 'en'
                    ])
                    ->setMaxResults(1)
                    ->getQuery();
                $versions = [
                    'igifgpcj2' => ['subject' => 'Paid campaign for %s'],
                    'igifgbij2' => ['subject' => 'Business inquiry for %s']
                ];
                $template = '@FangoMail/invitation.html.twig';
                $emailPerMinute = 40;
                break;
            case 2:
                $query = $em->createQueryBuilder()
                    ->select('m')
                    ->from('SeferovMailerBundle:Mail', 'm')
                    ->where('m.lastBatch != :lastBatch or m.lastBatch is null')
                    ->andWhere('m.unsubscribed = false')
                    ->andWhere('m.lang = :lang')
                    ->andWhere('m.problem = false')
                    ->andWhere('m.contacted = false')
                    ->andWhere('m.source = :source')
                    ->setParameters([
                        'lastBatch' => $batchNumber,
                        'source' => 'twitter',
                        'lang' => 'en'
                    ])
                    ->setMaxResults(1)
                    ->getQuery();
                $versions = [
                    'igifgtcj' => ['subject' => 'Twitter campaign for %s'],
                    'igifgfcj' => ['subject' => 'Facebook campaign for %s']
                ];
                $template = '@FangoMail/invitation2.html.twig';
                $emailPerMinute = 50;
                break;
            case 3:
            default:
                $query = $em->createQueryBuilder()
                    ->select('m')
                    ->from('SeferovMailerBundle:Mail', 'm')
                    ->where('m.lastBatch != :lastBatch or m.lastBatch is null')
                    ->andWhere('m.unsubscribed = false')
                    ->andWhere('m.lang = :lang')
                    ->andWhere('m.problem = false')
                    ->andWhere('m.contacted = false')
                    ->andWhere('m.source = :source')
                    ->setParameters([
                        'lastBatch' => $batchNumber,
                        'source' => 'twitter',
                        'lang' => 'en'
                    ])
                    ->setMaxResults(1)
                    ->getQuery();
                $versions = [
                    'tcfgsmj' => ['subject' => 'Twitter campaign for %s'],
                    'bifgsmj' => ['subject' => 'Business inquiry for @%s']
                ];
                $template = '@FangoMail/invitation3.html.twig';
                $emailPerMinute = 50;
                break;
            case 4:
                $query = $em->createQueryBuilder()
                    ->select('m')
                    ->from('SeferovMailerBundle:Mail', 'm')
                    ->where('m.lastBatch = :lastBatch')
                    ->andWhere('m.unsubscribed = false')
                    ->andWhere('m.lang = :lang')
                    ->andWhere('m.problem = false')
                    ->andWhere('m.contacted = false')
                    ->andWhere('m.source = :source')
                    ->setParameters([
                        'lastBatch' => 3,
                        'source' => 'twitter',
                        'lang' => 'en'
                    ])
                    ->setMaxResults(1)
                    ->getQuery();
                $versions = [
                    'tcfgsmj' => ['subject' => 'Twitter campaign for %s'],
                    'bifgsmj' => ['subject' => 'Business inquiry for @%s']
                ];
                $template = '@FangoMail/invitation4.html.twig';
                $emailPerMinute = 40;
                break;
        }

        for ($i = 0; $i < $emailPerMinute; $i++) {
            $version = array_rand($versions);
            $uid = md5(uniqid(mt_rand(), true));

            $em->getConnection()->beginTransaction();

            try {
                /** @var \Seferov\MailerBundle\Entity\Mail $email */
                $email = $query->setLockMode(LockMode::PESSIMISTIC_WRITE)->getSingleResult();

                // Get the last version sent and overwrite version accordingly
                if (in_array($batchNumber, [4]) && count($email->getBatches())) {
                    $version = $email->getBatches()->last()->getVersion();
                }
            }
            catch (NoResultException $e) {
                $em->getConnection()->close();
                break;
            }

            if (!filter_var($email->getMail(), FILTER_VALIDATE_EMAIL)) {
                $output->writeln($email->getMail() . ' <info>Not valid</info>');
                $email->setProblem(true);
                $em->persist($email);
                $em->flush();
                $em->getConnection()->commit();
                continue;
            }

            // Send mail
            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf($versions[$version]['subject'], $email->getUsername()))
                    ->setFrom(['jessica@fango.me' => 'Jessica Taylor'])
                    ->setTo($email->getMail())
                    ->setBody($templating->render($template, [
                        'version' => $version,
                        'uid' => $uid
                    ]), 'text/html');
                $mailer->send($message);
            }
            catch (\Swift_SwiftException $e) {
                $email->setProblem(true);
            }

            // Save to DB
            $batch = new Mail\Batch();
            $batch->setVersion($version);
            $batch->setMail($email);
            $batch->setUid($uid);
            $batch->setSentAt(new \DateTime('now'));
            $email->setLastBatch($batchNumber);

            $em->persist($batch, $email);
            $em->flush();
            $em->getConnection()->commit();

            $output->writeln($email->getMail());

            // Wait for a second
            sleep(1);
        }

        $output->writeLn('Done!');
    }
}