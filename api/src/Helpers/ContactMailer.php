<?php


namespace App\Helpers;


class ContactMailer extends Mailer
{
    public function contact(
        string $email,
        string $firstname,
        string $lastname,
        string $message
    ): void
    {
        $this->send(
            $email,
            'stephanechouchana@gmail.com',
            \sprintf(
                'Nouveau message de %s %s',
                $firstname,
                $lastname
            ),
            'contact',
            [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'message' => $message
            ]
        );
    }
}
