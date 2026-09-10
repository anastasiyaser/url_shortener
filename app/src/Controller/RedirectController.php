<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Service\UrlServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RedirectController.
 */
class RedirectController extends AbstractController
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
            throw $this->createNotFoundException($this->translator->trans('message.record_not_found'));
        }

        if ($url->isBlocked()) {
            throw $this->createNotFoundException($this->translator->trans('message.record_not_found'));
        }

        return $this->redirect($url->getOriginalUrl());
    }
}
