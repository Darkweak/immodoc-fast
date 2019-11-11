<?php


namespace App\Controller;


use App\Entity\File;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Welcome extends CommonController
{
    public function __invoke()
    {
        $files = $this->entityManager->getRepository(File::class)->findAll();

        return $this->render(
            'welcome',
            [
                'background_title' => \sprintf('Bienvenue sur %s', getenv('APP_NAME')),
                'background_description' => 'Votre partenaire pour vos mandats immobiliers',
                'files' => $files,
                'files_name_attribute' => Files::FIELD_NAME_FILES,
                'files_selected' => $this->session->get(Files::FIELD_NAME_FILES),
            ]
        );
    }
/*
    public function generate(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        for ($i = 0; $i < 20; $i++) {
            $current = new File();
            $current->setDescription(\sprintf('My awesome biggerdescription to describe my file number %s', $i));
            $current->setName(\sprintf('My file number %s', $i));
            $current->setPath(\sprintf('/mypath/myfile%s', $i));
            $current->setPrice(rand(0,10000)/100);
            $this->entityManager->persist($current);
        }
        $user = new User();
        $user->setEmail('stephanechouchana')
            ->setPassword($userPasswordEncoder->encodePassword($user, 'passw0rd'));
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $this->render(
            'welcome',
            [
                'background_title' => \sprintf('Bienvenue sur %s', getenv('APP_NAME')),
                'background_description' => 'Votre partenaire pour vos mandats immobiliers',
            ]
        );
    }*/
}
