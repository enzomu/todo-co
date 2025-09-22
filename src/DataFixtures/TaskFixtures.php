<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $anonymousUser = $manager->getRepository(User::class)->findOneBy(['username' => 'anonyme']);
        $admin = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $user = $manager->getRepository(User::class)->findOneBy(['username' => 'user']);

        $anonymousTasks = [
            ['ancienne tâche 1', 'Contenu de l\'ancienne tâche 1', true],
            ['ancienne tâche 2', 'Contenu de l\'ancienne tâche 2', false],
        ];

        foreach ($anonymousTasks as [$title, $content, $isDone]) {
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($content);
            $task->setAuthor($anonymousUser);
            $task->setIsDone($isDone);

            $task->setCreatedAt(new \DateTimeImmutable('-' . rand(30, 365) . ' days'));

            $manager->persist($task);
        }

        $adminTasks = [
            ['tâche admin1', 'contenu de la tâche admoin1', false],
            ['tâche admin2', 'contenu de la tâche admoin2', true],
        ];

        foreach ($adminTasks as [$title, $content, $isDone]) {
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($content);
            $task->setAuthor($admin);
            $task->setIsDone($isDone);
            $task->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));

            $manager->persist($task);
        }

        $userTasks = [
            ['tâche user1', 'contenu de la tâche admoin1', false],
            ['tâche user2', 'contenu de la tâche admoin2', true],
        ];

        foreach ($userTasks as [$title, $content, $isDone]) {
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($content);
            $task->setAuthor($user);
            $task->setIsDone($isDone);
            $task->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 15) . ' days'));

            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}