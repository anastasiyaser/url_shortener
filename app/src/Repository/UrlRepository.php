<?php

/**
 * Url repository.
 */

namespace App\Repository;

use App\Dto\UrlListInputFiltersDto;
use App\Entity\Url;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UrlRepository.
 *
 * @extends ServiceEntityRepository<Url>
 */
class UrlRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(UrlListInputFiltersDto $filters, ?User $user = null): QueryBuilder
    {
        // 1. Твой базовый запрос с partial-выборкой (оставляем без изменений)
        $qb = $this->createQueryBuilder('url')
            ->select(
                'partial url.{id, createdAt, updatedAt, originalUrl, shortCode, guestEmail, clickCount}',
                'partial tag.{id, name, createdAt}'
            )
            ->leftJoin('url.tags', 'tag')
            ->orderBy('url.id', 'DESC'); // Добавим сортировку от новых к старым (профессор просил)

        // 2. Твой варсунок безопасности (работает как и раньше)
        if (null !== $user && !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $qb->andWhere('url.user = :user')
                ->setParameter('user', $user);
        }

        // 3. НОВАЯ ЛОГИКА: Фильтрация по тегу из DTO
        if (null !== $filters->tagId) {
            $qb->andWhere('tag.id = :tagId')
                ->setParameter('tagId', $filters->tagId);
        }

        return $qb;
    }

    /**
     * Save entity.
     *
     * @param Url $url Url entity
     */
    public function save(Url $url): void
    {
        $this->getEntityManager()->persist($url);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Url $url Url entity
     */
    public function delete(Url $url): void
    {
        $this->getEntityManager()->remove($url);
        $this->getEntityManager()->flush();
    }
}
