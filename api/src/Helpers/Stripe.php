<?php


namespace App\Helpers;


use App\Controller\Files;
use App\Entity\File;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Stripe
{
    private $intent;
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
    }

    public function getIntent(): PaymentIntent
    {
        return $this->intent;
    }

    public function generateIntent(): PaymentIntent
    {
        try {
            $amount = 0;

            /** @var File $file */
            foreach ($this->session->get(Files::FIELD_NAME_FILES) as $file) {
                $amount += $file->getPrice();
            }

            $this->intent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'eur',
            ]);
            return $this->intent;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function retrieveIntent(string $intentId): ?PaymentIntent
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        try {
            return PaymentIntent::retrieve($intentId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
