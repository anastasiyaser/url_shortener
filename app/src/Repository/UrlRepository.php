<?php

/**
 * Url repository.
 */

namespace App\Repository;

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
    public function queryAll(?User $user = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('url')
            ->select(
                'partial url.{id, createdAt, updatedAt, originalUrl, shortCode, guestEmail, clickCount}',
                'partial tag.{id, name, createdAt}'
            )
            ->leftJoin('url.tags', 'tag');
        // 4. Warunek bezpieczeństwa: jeśli zalogowany i NIE jest adminem -> widzi tylko swoje linki
        if ($user !== null && !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $qb->andWhere('url.user = :user')
                ->setParameter('user', $user);
        }

        // Jeśli $user to null (gość) lub Admin -> baza zwróci wszystkie rekordy
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
