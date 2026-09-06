<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolver;

use App\Dto\UrlListInputFiltersDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Url list input filters DTO resolver.
 */
class UrlListInputFiltersDtoResolver implements ValueResolverInterface
{
    /**
     * Resolve value.
     *
     * @param Request          $request  HTTP request
     * @param ArgumentMetadata $argument Argument metadata
     *
     * @return iterable<UrlListInputFiltersDto> Iterable list of DTOs
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, UrlListInputFiltersDto::class, true)) {
            return [];
        }

        $tagId = $request->query->get('tagId') ? (int) $request->query->get('tagId') : null;

        return [new UrlListInputFiltersDto($tagId)];
    }
}
