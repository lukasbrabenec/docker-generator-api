<?php

namespace App\Repository;

use App\Entity\ImageVersionExtension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageVersionExtension|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageVersionExtension|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageVersionExtension[]    findAll()
 * @method ImageVersionExtension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageVersionExtensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageVersionExtension::class);
    }
}
