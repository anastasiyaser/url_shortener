<?php
/**
 * Url controller.
 */

namespace App\Controller;

use App\Entity\Url;
use App\Form\Type\UrlType;
use App\Service\UrlService;
use App\Service\UrlServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class UrlController.
 */
#[Route('/url')]
class UrlController extends AbstractController
{
    /**
     * Constructor.
     */
    public function __construct(private readonly UrlServiceInterface $urlService)
    {
    }
    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'url_index',
        methods: ['GET']
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->urlService->getPaginatedList($page);

        return $this->render('url/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View action.
     *
     * @param Url $url Url entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'url_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET']
    )]
    public function view(Url $url): Response
    {
        return $this->render(
            'url/view.html.twig',
            ['url' => $url]
        );
    }
}
