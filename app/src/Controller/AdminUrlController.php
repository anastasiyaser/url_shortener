<?php

/**
 * Admin URL controller.
 */

namespace App\Controller;

use App\Entity\Url;
use App\Service\UrlServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminUrlController.
 */
#[Route('/admin/url')]
#[IsGranted('ROLE_ADMIN')]
class AdminUrlController extends AbstractController
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
     * Toggle block status action.
     *
     * @param Url $url Url entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/toggle-block', name: 'admin_url_toggle_block', methods: ['POST'])]
    public function toggleBlock(Url $url): Response
    {
        $url->setIsBlocked(!$url->isBlocked());
        $this->urlService->save($url);

        $this->addFlash(
            'success',
            $this->translator->trans('message.status_updated')
        );

        return $this->redirectToRoute('url_index');
    }
}
