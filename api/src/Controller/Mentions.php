<?php


namespace App\Controller;


use App\Entity\File;

class Mentions extends CommonController
{
    public function __invoke()
    {
        $files = $this->entityManager->getRepository(File::class)->findAll();

        return $this->render(
            'mentions',
            [
                'background_title' => 'Mentions légales',
                'maintainer' => 'COMBRAQUE Sylvain',
                'maintainer_email' => 'sylvaincombraque@hotmail.fr',
                'maintainer_website' => 'https://devcv.fr',
                'website_basepath' => 'https://mandatstore.com',
            ]
        );
    }
}
