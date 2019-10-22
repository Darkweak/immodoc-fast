<?php


namespace App\Helpers;


use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Mailer as SFMailer;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    private $environment;
    private $mailer;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $transport = new GmailTransport(getenv('EMAIL_USER'), getenv('EMAIL_PASS'));
        $this->mailer = new SFMailer($transport);
    }

    protected function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $options = []
    ): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->replyTo($from)
            ->subject($subject)
            ->html(
                $this->environment
                    ->render(
                        \sprintf('Emails/%s.html.twig', $template),
                        \array_merge(
                            [
                                'app_name' => getenv('APP_NAME')
                            ],
                            $options
                        )
                    )
            );

        $this->mailer->send($email);
    }
}
