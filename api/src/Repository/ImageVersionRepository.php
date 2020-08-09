<?php

namespace App\Repository;

use App\Entity\ImageVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ImageVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageVersion[]    findAll()
 * @method ImageVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageVersion::class);
    }
}
