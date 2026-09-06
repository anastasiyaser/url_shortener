<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Dto;

/**
 * Class UrlListInputFiltersDto.
 */
class UrlListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $tagId Tag identifier
     */
    public function __construct(public readonly ?int $tagId = null)
    {
    }
}
