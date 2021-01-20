<?php

namespace App\Repository;

use App\Entity\RestartType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestartType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestartType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestartType[]    findAll()
 * @method RestartType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestartTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestartType::class);
    }
}
