<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskControllerTest extends WebTestCase
{
    private function createTestUser(string $suffix = ''): User
    {
        $user = new User();
        $user->setUsername('u' . rand(100, 999) . $suffix);
        $user->setEmail('test' . rand(100, 999) . $suffix . '@example.com');
        return $user;
    }

    public function testTaskCreatePage(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_create');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();
    }

    public function testTaskListPage(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_list');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    public function testTaskEditPage(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_edit');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('Test Content');
        $task->setAuthor($user);

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($task);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertResponseIsSuccessful();
    }

    public function testTaskDeleteAccess(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_delete');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $task = new Task();
        $task->setTitle('Delete Task');
        $task->setContent('Delete Content');
        $task->setAuthor($user);

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($task);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertResponseRedirects('/tasks');
    }

    public function testTaskToggle(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_toggle');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $task = new Task();
        $task->setTitle('Toggle Task');
        $task->setContent('Toggle Content');
        $task->setAuthor($user);
        $task->setIsDone(false);

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($task);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertResponseRedirects('/tasks');
    }

    public function testTaskCreatePost(): void
    {
        $client = static::createClient();

        $user = $this->createTestUser('_post');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'New Task';
        $form['task[content]'] = 'New Content';

        $client->submit($form);
        $this->assertResponseRedirects('/tasks');
    }
}