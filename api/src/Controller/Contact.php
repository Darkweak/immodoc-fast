<?php


namespace App\Controller;


class Contact extends CommonController
{
    public function __invoke()
    {
        return $this->render(
            'contact',
            [
                'background_title' => 'Contactez-nous',
                'background_description' => 'Nous vous répondrons dans les plus bref délais'
            ]
        );
    }
}
