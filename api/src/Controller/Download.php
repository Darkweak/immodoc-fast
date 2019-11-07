<?php


namespace App\Controller;


use App\Action\File;
use App\Entity\CryptedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Download extends CommonController
{
    public function __invoke(string $token)
    {
        $cryptedFile = $this->entityManager->getRepository(CryptedFile::class)->findOneBy(['token' => $token]);

        if ($cryptedFile instanceof CryptedFile) {
            $this->entityManager->remove($cryptedFile);
            $this->entityManager->flush();

            $path = \sprintf('%s/../..%s/%s', __DIR__, File::PATH_FOLDER, $cryptedFile->getFile()->getPath());
            $response = new BinaryFileResponse($path);

            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $cryptedFile->getFile()->getPath());
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }

        return new RedirectResponse(
            $this->router->generate(
                'welcome'
            )
        );
    }
}
