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

    /**
     * Trouve toutes les tâches d'un utilisateur spécifique
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.author = :user')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les tâches terminées d'un utilisateur
     */
    public function findCompletedByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->andWhere('t.isDone = true')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les tâches non terminées d'un utilisateur
     */
    public function findPendingByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->andWhere('t.isDone = false')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les tâches anonymes (pour les admins)
     */
    public function findAnonymousTasks(): array
    {
        return $this->createQueryBuilder('t')
            ->join('t.user', 'u')
            ->andWhere('u.username = :anonymous')
            ->setParameter('anonymous', 'anonyme')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les tâches d'un utilisateur par statut
     */
    public function countByUserAndStatus(User $user, bool $isDone): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.user = :user')
            ->andWhere('t.isDone = :isDone')
            ->setParameter('user', $user)
            ->setParameter('isDone', $isDone)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouve les tâches créées dans une période donnée
     */
    public function findByDateRange(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}