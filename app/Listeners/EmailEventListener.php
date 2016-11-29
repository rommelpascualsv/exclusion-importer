<?php 
namespace App\Listeners;

use App\Events\EmailEvent;

class EmailEventListener extends Listener
{

    /*private $mailer;
    public function __construct(Mailer $newMailer)
    {
        $this->mailer = $newMailer;
    }*/

    public function handle(EmailEvent $event)
    {

        // Should be like this
        $emailService = new EmailService($event->getEmailNotification());
        $emailService->send();

        /*$emailNotification = $event->getEmailNotification();
        $this->mailer->send($event->getEmailTemplate(), function($message) use ($event)
        {
            $message->to($event->getRecipients())
                ->subject($event->getSubject());
                //->setBody();
        }
        );*/
        info($event->getMessage());
    }
}