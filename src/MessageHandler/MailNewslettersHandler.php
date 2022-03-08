<?php

namespace App\MessageHandler;

use App\Message\Mail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailNewslettersHandler implements MessageHandlerInterface
{
    private $mailer;

    private $parameterBag;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag )
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;

    }

    public function __invoke(Mail $mail)
    {

        $finder = new Finder();

        $finder->files()->in($this->parameterBag->get('uploads_directory'));
        $email = new Email();
        $email->from(new Address('anne@everard.com', 'Anne Everard'));
        $email->to($mail->getEmailUser());
        $email->subject($mail->getSubject());
        if ($finder->hasResults()){
            foreach ($finder as $file){
                $email->attachFromPath($file->getRealPath());
            }
        }
        $email->html($mail->getContent());

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e){
                dd($e);
            }
    }


}