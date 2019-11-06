<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class CommonController
{
    protected $entityManager;
    private $environment;
    protected $router;
    protected $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        Environment $environment,
        RouterInterface $router,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->environment = $environment;
        $this->router = $router;
        $this->session = $session;
    }

    protected function render(string $template, array $options = []): Response
    {
        return new Response(
            $this->environment->render(
                \sprintf('Views/%s.html.twig', $template),
                \array_merge(
                    [
                        'app_city' => 'CODE POSTAL',
                        'app_name' => getenv('APP_NAME'),
                        'app_postcode' => '00000',
                        'app_street' => "1 rue de l'espace",
                        'background_image' => $template,
                        'number_selected_files' => \count($this->session->get(Files::FIELD_NAME_FILES) ?? [])
                    ],
                    $options
                )
            )
        );
    }
}
