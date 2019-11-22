<?php


namespace App\Helpers;


class NotifyMailer extends Mailer
{
    public function notify(string $id): void
    {
        $this->sendWithoutTemplate(
            'notifications@mandatstore.com',
            'notifications@mandatstore.com',
            $id
        );
    }

    public function testNotify(string $id) {
        $this->sendWithoutTemplate(
            'test-notifications@mandatstore.com',
            'test-notifications@mandatstore.com',
            $id
        );
    }
}
