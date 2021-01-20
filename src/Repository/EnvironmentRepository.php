<?php

namespace App\Repository;

use App\Entity\ImageEnvironment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageEnvironment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageEnvironment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageEnvironment[]    findAll()
 * @method ImageEnvironment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvironmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageEnvironment::class);
    }
}
