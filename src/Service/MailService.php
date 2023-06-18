<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;


class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $from, string $to, string $subject, string $content): void
    {
        
        $transport = Transport::fromDsn('smtp://winjoy012@gmail.com:ngmzmnrxtqresokn@smtp.gmail.com:465');
        $mailer = new Mailer($transport);
        
        $email = (new Email());
        
        $email->from($from);
        
        $email->to($to);
        
        $email->subject($subject);
        
        $email->text($content);
        
        $email->html('
            <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
            Congratulations, you WON the giveaway! You will receive the prize soon!  
            </h1>');
        
        $mailer->send($email);

    }
}