<?php

/**
 * Redirect controller.
 *
 * (c) Your Name / University License
 */

namespace App\Controller;

use App\Service\UrlServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class RedirectController.
 */
class RedirectController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UrlServiceInterface $urlService Url service
     */
    public function __construct(private readonly UrlServiceInterface $urlService)
    {
    }

    /**
     * Redirect to original URL action.
     *
     * @param string $shortCode Short code
     *
     * @return Response HTTP response
     */
    #[Route('/{shortCode}', name: 'url_redirect', requirements: ['shortCode' => '(?!(?:logout))[a-zA-Z0-9]{6}'], methods: ['GET'])]
    public function redirectUrl(string $shortCode): Response
    {
        $url = $this->urlService->getUrlForRedirect($shortCode);

        if (null === $url) {
            throw $this->createNotFoundException('Ссылка не найдена.');
        }

        if (!$this->isGranted('URL_VIEW', $url)) {
            throw $this->createAccessDeniedException('Доступ к этой ссылке запрещен.');
        }

        return $this->redirect($url->getOriginalUrl());
    }
}
