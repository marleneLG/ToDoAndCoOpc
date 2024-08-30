<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllTaskByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.user = :task')
            ->setParameter('task', $user);

        return $qb->getQuery()->getResult();
    }

    public function findAllTaskByUserAdmin(User $user): array
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.user = :task OR t.user IS NULL')
            ->setParameter('task', $user);
        return $qb->getQuery()->getResult();
    }
}
