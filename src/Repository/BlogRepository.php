<?php

namespace App\Repository;

use App\Entity\Blog;
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
                    ->where('blog.archived = false')
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

    public function getFiveMostCommented(): array
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT b.id, b.title, COUNT(c.id) as num_comments
            FROM App\Entity\Blog b
            JOIN b.commentaries c
            WHERE b.archived = false
            GROUP BY b.id
            ORDER BY num_comments DESC
         ');
        return $query->setMaxResults(5)->getResult();

    }

    public function findLatest(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('blog')
            ->orderBy('blog.publish_date', 'DESC')
            ->where('blog.archived = false')
        ;


        return (new Paginator($qb))->paginate($page);
    }

    public function getImageFiles(Blog $blog): array
    {
        $files = array();
        foreach($blog->getImages() as $image)
            if (!$image->isIsLead())
                $files[] = [$image->getFilename(), $image->getId()];
        return $files;
    }

}
