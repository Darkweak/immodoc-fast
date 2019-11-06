<?php


namespace App\Action;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

class File
{
    public const PATH_FOLDER = '/files/mypath';

    private $checker;
    private $entityManager;
    private $serializer;
    private $tokenStorage;

    public function __construct(
        AuthorizationCheckerInterface $checker,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->checker = $checker;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
    }

    private function serializeString(string $value): string
    {
        return preg_replace('/\ +/', '-', strtolower($value));
    }

    private function getExtensionFromFilename(string $filename): string
    {
        $splitted = explode('.', $filename);
        return $splitted[count($splitted) - 1];
    }

    private function checkGranted()
    {
        if (!$this->checker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
    }

    public function __invoke(Request $request)
    {
        $this->checkGranted();
        try {
            $file = $request->files->get('file');
            $values = $request->request->all();

            $filename = \sprintf(
                '%s.%s',
                $this->serializeString($values['name']),
                $this->getExtensionFromFilename($file->getClientOriginalName())
            );

            $file->move(
                \sprintf(
                    '%s/../..%s',
                    __DIR__,
                    self::PATH_FOLDER
                ),
                $filename
            );

            $newFile = (new \App\Entity\File())
                ->setPath($filename)
                ->setPrice($values['price'])
                ->setName($values['name'])
                ->setDescription($values['description']);

            $this->entityManager->persist($newFile);
            $this->entityManager->flush();
            return new JsonResponse(\json_decode($this->serializer->serialize($newFile, 'json')));
        } catch (\Exception $e) {
            \dump($e);
        }
    }

    public function update(Request $request)
    {
        $this->checkGranted();
        try {
            $file = $request->files->get('file');
            $values = $request->request->all();

            $newFile = $this->entityManager->getRepository(\App\Entity\File::class)->find($values['id']);

            $newFile->setPrice($values['price'])
                ->setName($values['name'])
                ->setDescription($values['description']);

            if ($file) {
                $filename = \sprintf(
                    '%s.%s',
                    $this->serializeString($values['name']),
                    $this->getExtensionFromFilename($file->getClientOriginalName())
                );

                $file->move(
                    \sprintf(
                        '%s/../..%s',
                        __DIR__,
                        self::PATH_FOLDER
                    ),
                    $filename
                );

                $newFile->setPath($filename);
            }

            $this->entityManager->flush();
            return new JsonResponse(\json_decode($this->serializer->serialize($newFile, 'json')));
        } catch (\Exception $e) {
            return new Response($e);
        }
    }
}
