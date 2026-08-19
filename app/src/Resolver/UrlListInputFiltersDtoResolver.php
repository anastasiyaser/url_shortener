<?php
declare(strict_types=1);

namespace App\Resolver;

use App\Dto\UrlListInputFiltersDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UrlListInputFiltersDtoResolver implements ValueResolverInterface
{
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
