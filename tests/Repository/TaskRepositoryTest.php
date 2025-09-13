<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->taskRepository = $this->entityManager->getRepository(Task::class);
    }

    public function testFindByUser(): void
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('test@example.com');
        $user->setPassword('password');

        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('Test Content');
        $task->setAuthor($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $tasks = $this->taskRepository->findByUser($user);

        $this->assertCount(1, $tasks);
        $this->assertEquals('Test Task', $tasks[0]->getTitle());

        $this->entityManager->remove($task);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}