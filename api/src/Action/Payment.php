<?php


namespace App\Action;


use App\Controller\Files;
use App\Entity\CryptedFile;
use App\Entity\File;
use App\Entity\Payment as PaymentEntity;
use App\Helpers\PaymentMailer;
use App\Helpers\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class Payment extends PaymentMailer
{
    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, Environment $environment, SessionInterface $session)
    {
        parent::__construct($environment);
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function __invoke(Request $request)
    {
        $content = json_decode($request->getContent());

        $paymentCheck = $this->entityManager
            ->getRepository(PaymentEntity::class)
            ->findOneBy(['intentId' => $content->intent]);

        if ($paymentCheck instanceof PaymentEntity) {
            throw new \Exception();
        }

        $paymentIntent = Stripe::retrieveIntent($content->intent);
        if (!$paymentIntent || $paymentIntent->status !== 'succeeded') {
            throw new \Exception();
        }

        $payment = (new PaymentEntity())
            ->setAmount($paymentIntent->amount)
            ->setEmail($content->email)
            ->setIntentId($content->intent)
            ->setTransactionDate(new \DateTime());

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $cryptedFiles = $this->logPaymentAndGenerateCryptedFiles($payment);

        $this->notify(
            $content->email,
            $cryptedFiles,
            $payment
        );


        $this->session->set(Files::FIELD_NAME_FILES, []);
        return new JsonResponse([]);
    }

    private function logPaymentAndGenerateCryptedFiles(PaymentEntity $payment): array
    {
        $files = $this->session->get(Files::FIELD_NAME_FILES);
        $cryptedFiles = [];

        /** @var File $file */
        foreach ($files as $file) {
            $cryptedFilename = $this->hashCryptedFile($file->getName());
            $cryptedFile = (new CryptedFile())
                ->setPayment($payment)
                ->setFile($this->entityManager->getRepository(File::class)->findOneBy(['id' => $file->getId()]))
                ->setUsed(false)
                ->setToken($cryptedFilename);

            $this->entityManager->persist($cryptedFile);
            $this->entityManager->flush();

            array_push(
                $cryptedFiles,
                $cryptedFile
            );
        }

        return $cryptedFiles;
    }

    private function hashCryptedFile(string $filename): string
    {
        return hash('sha512', (new \DateTime())->format('Y-m-d H:i:s') . $filename . getenv("APP_NAME"));
    }
}
