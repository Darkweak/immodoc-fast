<?php


namespace App\Helpers;


use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;

class PaymentMailer extends Mailer
{
    public function notify(
        string $email,
        EntityManagerInterface $manager
    ): void
    {
        $payment = $manager->getRepository(Payment::class)->findBy([
            'email' => $email],
            ['transactionDate' => 'DESC']
        )[0];

        if ($payment instanceof Payment) {
            $this->send(
                getenv('EMAIL_USER'),
                $email,
                'Vos fichiers commandés',
                'payment',
                [
                    'files' => $payment->getCryptedFiles()
                ]
            );
        }
    }
}
