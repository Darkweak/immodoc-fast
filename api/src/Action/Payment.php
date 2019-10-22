<?php


namespace App\Action;


use App\Helpers\PaymentMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class Payment extends PaymentMailer
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Environment $environment)
    {
        parent::__construct($environment);
    }

    public function __invoke(Request $request)
    {
        try {
            $content = json_decode($request->getContent());

            $this->notify(
                $content->email,
                $this->entityManager
            );
        } catch (\Exception $e) {}

        return new JsonResponse([]);
    }
}
