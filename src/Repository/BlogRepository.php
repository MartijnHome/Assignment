<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function save(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLatestFive(): array
    {
        return $this->createQueryBuilder('blog')
                    ->orderBy('blog.publish_date', 'DESC')
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult();
    }

    public function getPublished(): array
    {
        return $this->createQueryBuilder('blog')
            ->orderBy('blog.publish_date', 'DESC')
            ->where('blog.archived = false')
            ->getQuery()
            ->getResult();
    }

    public function findLatest(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('blog')
            ->orderBy('blog.publish_date', 'DESC')
            ->where('blog.archived = false')
        ;


        return (new Paginator($qb))->paginate($page);
    }

}
