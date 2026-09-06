<?php

/**
 * Url service.
 *
 * (c) Your Name / University License
 */

namespace App\Service;

use App\Dto\UrlListInputFiltersDto;
use App\Entity\Url;
use App\Entity\User;
use App\Repository\UrlRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UrlService.
 */
class UrlService implements UrlServiceInterface
{
    /**
     * Items per page.
     *
     * @var int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param UrlRepository      $urlRepository Url repository
     * @param PaginatorInterface $paginator     Paginator
     */
    public function __construct(private readonly UrlRepository $urlRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list of URLs.
     *
     * @param int                    $page    Page number
     * @param User|null              $user    Current user
     * @param UrlListInputFiltersDto $filters Filters DTO
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, ?User $user, UrlListInputFiltersDto $filters): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->urlRepository->queryAll($filters, $user),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['url.id', 'url.createdAt', 'url.updatedAt', 'url.clickCount'],
                'defaultSortFieldName' => 'url.createdAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     *
     * @param Url $url Url entity
     *
     * @throws \Exception If random generation fails
     */
    public function save(Url $url): void
    {
        if (null === $url->getId()) {
            if (null === $url->getShortCode()) {
                $uniqueCode = $this->generateUniqueShortCode();
                $url->setShortCode($uniqueCode);
            }
        }

        $this->urlRepository->save($url);
    }

    /**
     * Delete entity.
     *
     * @param Url $url Url entity
     */
    public function delete(Url $url): void
    {
        $this->urlRepository->delete($url);
    }

    /**
     * Generates a unique shortcode.
     *
     * @return string Generated shortcode
     *
     * @throws \Exception If random generation fails
     */
    private function generateUniqueShortCode(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $length = 6;

        do {
            $shortCode = '';
            for ($i = 0; $i < $length; ++$i) {
                $shortCode .= $characters[random_int(0, $charactersLength - 1)];
            }

            $existingUrl = $this->urlRepository->findOneBy(['shortCode' => $shortCode]);
        } while (null !== $existingUrl);

        return $shortCode;
    }
}
