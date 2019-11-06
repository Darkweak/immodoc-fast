<?php


namespace App\Helpers;


use App\Entity\Payment;

class PaymentMailer extends Mailer
{
    public function notify(
        string $email,
        array $files,
        Payment $payment
    ): void
    {
        $this->send(
            getenv('EMAIL_USER'),
            $email,
            'Vos fichiers commandés',
            'payment',
            [
                'files' => $files,
                'payment' => $payment
            ]
        );
    }
}
