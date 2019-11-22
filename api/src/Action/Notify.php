<?php


namespace App\Action;


use App\Helpers\NotifyMailer;
use Symfony\Component\HttpFoundation\JsonResponse;

class Notify extends NotifyMailer
{
    public function __invoke()
    {
        $this->notify();

        return new JsonResponse([]);
    }
    public function test(string $id)
    {
        $this->testNotify($id);

        return new JsonResponse([]);
    }
}
