<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param UrlListInputFiltersDto $filters Input filters DTO
     * @param User|null              $user    User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(UrlListInputFiltersDto $filters, ?User $user = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('url')
            ->select(
                'partial url.{id, createdAt, updatedAt, originalUrl, shortCode, guestEmail, clickCount}',
                'partial tag.{id, name, createdAt}'
            )
            ->leftJoin('url.tags', 'tag')
            ->orderBy('url.id', 'DESC');

        if (null !== $user && !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $qb->andWhere('url.user = :user')
                ->setParameter('user', $user);
        }

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
