<?php

namespace App\Repository;

use App\Entity\ComposeFormatVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComposeFormatVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComposeFormatVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComposeFormatVersion[]    findAll(array $criteria, array $orderBy = null)
 * @method ComposeFormatVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComposeFormatVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComposeFormatVersion::class);
    }

    public function findAllAndOrderBy(array $orderBy): array
    {
        return $this->findBy([], $orderBy);
    }
}
