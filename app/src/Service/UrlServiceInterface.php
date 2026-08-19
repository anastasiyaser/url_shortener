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
     * Get paginated list.
     *
     * @param int       $page Page number
     * @param User|null $user Current user
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, ?User $user, UrlListInputFiltersDto $filters): PaginationInterface;

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
