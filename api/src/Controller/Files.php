<?php


namespace App\Controller;


use App\Entity\File;
use App\Helpers\Stripe;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Files extends CommonController
{
    public const FIELD_NAME_FILES = 'files';

    public function renderBasket(): Response
    {
        $params = [];
        if ($this->session->has(self::FIELD_NAME_FILES) && \count($this->session->get(self::FIELD_NAME_FILES))) {
            $intent = (new Stripe($this->session))->generateIntent();
            $params = ['intent_secret' => $intent->client_secret, 'intent_id' => $intent->id];

        }

        return $this->render('files/payment', array_merge(
            [
                'background_title' => 'Votre panier',
                'background_description' => 'Validation et paiement de votre panier',
                'files' => $this->session->get(self::FIELD_NAME_FILES),
                'public_key_stripe' => getenv('STRIPE_PUBLIC_KEY')
            ],
            $params
        ));
    }

    public function listFiles()
    {
        $files = $this->entityManager->getRepository(File::class)->findAll();

        return $this->render('files/list', [
            'background_title' => 'Liste des fichiers',
            'background_description' => 'disponibles avec leur prix',
            'files' => $files,
            'files_name_attribute' => self::FIELD_NAME_FILES,
            'files_selected' => $this->session->get(self::FIELD_NAME_FILES),
        ]);
    }

    public function downloadFile()
    {
        $this->render('files/download');
    }

    public function paymentFile_post()
    {
        try {
            $request = Request::createFromGlobals();
            $files_session = [];

            $files = $request->request->get(self::FIELD_NAME_FILES);

            if (!$files) {
                $files = [];
            }

            $existedFiles = $this->entityManager->getRepository(File::class)->findAll();

            foreach ($files as $k => $v) {
                /** @var File $existedFile */
                foreach ($existedFiles as $existedFile) {
                    if ($k === $existedFile->getName() && !\in_array($k, $files_session)) {
                        \array_push($files_session, $existedFile);
                    }
                }
            }

            $this->session->set(self::FIELD_NAME_FILES, $files_session);
            return $this->renderBasket();
        } catch (\Exception $e){
            return new RedirectResponse(
                $this->router->generate(
                    'fileList',
                    ['error' => 'Une erreur est survenue veuillez réessayer']
                )
            );
        }
    }

    public function paymentFile_clear()
    {
        $this->session->set(self::FIELD_NAME_FILES, []);
        return $this->renderBasket();
    }

    public function paymentFile_get()
    {
        return $this->renderBasket();
    }
}
