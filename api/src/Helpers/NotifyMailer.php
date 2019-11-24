<?php


namespace App\Helpers;


class NotifyMailer extends Mailer
{
    public function notify(string $id): void
    {
        $this->sendWithoutTemplate(
            'notifications@mandatstore.com'
        );
    }

    public function testNotify() {
        $this->send(
            'test-notifications@mandatstore.com',
            'sylvaincombraque@hotmail.fr',
            '',
            'notify'
        );
    }
}
