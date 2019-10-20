<?php


namespace App\Helpers;


use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Stripe
{
    private const STRIPE_INTENT_KEY = 'stripe_intent';

    private $intent;
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        \Stripe\Stripe::setApiKey('sk_test_tRFqRHn3UjWXEksK1mUrL5PS');
    }

    public function getIntent(): PaymentIntent
    {
        return $this->intent;
    }

    public function retrieveIntent(): PaymentIntent
    {
        if (null !== $this->intent) {
            return $this->intent;
        } else if ($this->session->has(self::STRIPE_INTENT_KEY)) {
            $this->intent = $this->session->get(self::STRIPE_INTENT_KEY);
            return $this->session->get(self::STRIPE_INTENT_KEY);
        } else {
            try {
                $this->intent = PaymentIntent::create([
                    'amount' => 1099,
                    'currency' => 'eur',
                ]);
                return $this->intent;
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
