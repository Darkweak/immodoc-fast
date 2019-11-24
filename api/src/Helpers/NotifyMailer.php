<?php


namespace App\Helpers;


class NotifyMailer extends Mailer
{
    public function notify(): void
    {
        $this->send(
            'notifications@mandatstore.com',
            'notifications@mandatstore.com',
            'Découvrez le service mandatstore.com',
            'notify'
        );
    }

    public function testNotify() {
        $this->send(
            'test-notifications@mandatstore.com',
            'test-notifications@mandatstore.com',
            'Découvrez le service mandatstore.com',
            'notify'
        );
    }
}
