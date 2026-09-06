<?php

/**
 * Url service interface.
 *
 * (c) Your Name / University License
 */

namespace App\Service;

use App\Dto\UrlListInputFiltersDto;
use App\Entity\Url;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UrlServiceInterface.
 */
interface UrlServiceInterface
{
    /**
     * Get paginated list of URLs.
     *
     * @param int                    $page    Page number
     * @param User|null              $user    Current user
     * @param UrlListInputFiltersDto $filters Filters DTO
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, ?User $user, UrlListInputFiltersDto $filters): PaginationInterface;

    /**
     * Get URL for redirect and increment click count.
     *
     * @param string $shortCode Short code
     *
     * @return Url|null Url entity or null if not found
     */
    public function getUrlForRedirect(string $shortCode): ?Url;

    /**
     * Save entity.
     *
     * @param Url $url Url entity
     */
    public function save(Url $url): void;

    /**
     * Delete entity.
     *
     * @param Url $url Url entity
     */
    public function delete(Url $url): void;
}
