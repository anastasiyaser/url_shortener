<?php
/**
 * Url controller.
 */

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request            $request        HTTP Request
     * @param UrlRepository     $urlRepository Url repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     */

    #[Route(
        name: 'url_index',
        methods: ['GET']
    )]
    public function index(Request $request, UrlRepository $urlRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $urlRepository->queryAll(),
            $request->query->getInt('page', 1),
            UrlRepository::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['url.id', 'url.createdAt', 'url.updatedAt'],
                'defaultSortFieldName' => 'url.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

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
