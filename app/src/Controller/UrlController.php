<?php
/**
 * Url controller.
 *
 * (c) Your Name / University License
 */

namespace App\Controller;

use App\Dto\UrlListInputFiltersDto;
use App\Entity\Tag;
use App\Entity\Url;
use App\Entity\User;
use App\Form\Type\UrlEditType;
use App\Form\Type\UrlType;
use App\Resolver\UrlListInputFiltersDtoResolver;
use App\Security\Voter\UrlVoter; // 🚀 IMPORT TWOJEGO VOTERA
use App\Service\UrlServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted; // 🚀 IMPORT ATRYBUTU BEZPIECZEŃSTWA
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UrlController.
 */
#[Route('/url')]
class UrlController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UrlServiceInterface $urlService Url service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(private readonly UrlServiceInterface $urlService, private readonly TranslatorInterface $translator)
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
    public function index(
        #[MapQueryString(resolver: UrlListInputFiltersDtoResolver::class)] ?UrlListInputFiltersDto $filters = null,
        #[MapQueryParameter] int $page = 1
    ): Response {
        // Если фильтры не пришли (например, просто зашли на главную), создаем пустой объект
        $filters ??= new UrlListInputFiltersDto();

        // Передаем фильтры третьим аргументом в сервис
        $pagination = $this->urlService->getPaginatedList($page, $this->getUser(), $filters);

        return $this->render('url/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'url_create',
        methods: ['GET', 'POST']
    )]
    public function create(Request $request): Response
    {
        $url = new Url();
        if ($this->getUser()) {
            $url->setUser($this->getUser());
            $url->setGuestEmail($this->getUser()->getEmail());
        }
        $form = $this->createForm(UrlType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->urlService->save($url);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('url_index');
        }

        return $this->render(
            'url/create.html.twig',
            ['form' => $form->createView()]
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
        '/{shortCode}',
        name: 'url_view',
        requirements: ['shortCode' => '[a-zA-Z0-9]{6}'],
        methods: ['GET']
    )]
    #[IsGranted(UrlVoter::VIEW, subject: 'url')] // 🚀 Kontrola dostępu przez Voter
    public function view(Url $url): Response
    {
        return $this->render(
            'url/view.html.twig',
            ['url' => $url]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Url     $url     Url entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{shortCode}/edit',
        name: 'url_edit',
        requirements: ['shortCode' => '[a-zA-Z0-9]{6}'],
        methods: ['GET', 'POST', 'PUT']
    )]
    #[IsGranted(UrlVoter::EDIT, subject: 'url')] // 🚀 Kontrola dostępu przez Voter
    public function edit(Request $request, Url $url): Response
    {
        $form = $this->createForm(
            UrlEditType::class,
            $url,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('url_edit', ['shortCode' => $url->getShortCode()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->urlService->save($url);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('url_index');
        }

        return $this->render(
            'url/edit.html.twig',
            [
                'form' => $form->createView(),
                'url' => $url,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Url     $url     Url entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{shortCode}/delete',
        name: 'url_delete',
        requirements: ['shortCode' => '[a-zA-Z0-9]{6}'],
        methods: ['GET', 'POST', 'DELETE']
    )]
    #[IsGranted(UrlVoter::DELETE, subject: 'url')] // 🚀 Kontrola dostępu przez Voter
    public function delete(Request $request, Url $url): Response
    {
        $form = $this->createForm(FormType::class, $url, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('url_delete', ['shortCode' => $url->getShortCode()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->urlService->delete($url);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('url_index');
        }

        return $this->render(
            'url/delete.html.twig',
            [
                'form' => $form->createView(),
                'url' => $url,
            ]
        );
    }
}
