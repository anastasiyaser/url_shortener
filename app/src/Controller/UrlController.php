<?php
/**
 * Url controller.
 */

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class UrlController.
 */
#[Route('/url')]
class UrlController extends AbstractController
{
    /**
     * Index action.
     *
     * @param UrlRepository $urlRepository Task repository
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'url_index',
        methods: ['GET']
    )]
    public function index(UrlRepository $urlRepository): Response
    {
        $urls = $urlRepository->findAll();

        return $this->render(
            'url/index.html.twig',
            ['urls' => $urls]
        );
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

