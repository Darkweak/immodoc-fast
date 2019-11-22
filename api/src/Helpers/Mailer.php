<?php


namespace App\Helpers;


use App\Entity\Email as EmailEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Bridge\Mailgun\Smtp\MailgunTransport;
use Symfony\Component\Mailer\Mailer as SFMailer;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    private $environment;
    private $mailer;
    private $manager;

    public function __construct(Environment $environment, EntityManagerInterface $manager)
    {
        $this->environment = $environment;
        $this->manager = $manager;
        $transport = new MailgunTransport(getenv('EMAIL_USER'), getenv('MAILGUN_KEY'), 'eu');
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
            )->text(
                $this->environment
                    ->render(
                        \sprintf('Emails/%s.txt.twig', $template),
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

    protected function sendWithoutTemplate(
        string $from,
        string $to,
        string $id
    ): void
    {
        $email = $this->manager->getRepository(EmailEntity::class)->find($id);

        if (!$email instanceof EmailEntity) {
            throw new \Exception();
        }

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->replyTo($from)
            ->subject($email->getName())
            ->html(
                $email->getContent()
            )->text(
                $email->getContent()
            );

        $this->mailer->send($email);
    }
}
