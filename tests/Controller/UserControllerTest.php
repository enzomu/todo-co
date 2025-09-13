<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    public function testUserListRequiresAdmin(): void
    {
        $client = static::createClient();

        $user = new User();
        $user->setUsername('normaluser' . uniqid());
        $user->setEmail('normal' . uniqid() . '@example.com');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $entityManager->clear();
    }

    public function testUserListWithAdmin(): void
    {
        $client = static::createClient();

        $admin = new User();
        $admin->setUsername('adminuser' . uniqid());
        $admin->setEmail('admin' . uniqid() . '@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $admin->setPassword($hasher->hashPassword($admin, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        $client->loginUser($admin);

        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();

        $entityManager->clear();
    }

    public function testUserCreate(): void
    {
        $client = static::createClient();

        $admin = new User();
        $admin->setUsername('createadmin' . uniqid());
        $admin->setEmail('createadmin' . uniqid() . '@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $admin->setPassword($hasher->hashPassword($admin, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        $client->loginUser($admin);

        $crawler = $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'newuser' . uniqid();
        $form['user[email]'] = 'newuser' . uniqid() . '@example.com';
        $form['user[password]'] = 'newpassword';
        $form['user[roles]'] = ['ROLE_USER'];

        $client->submit($form);
        $this->assertResponseRedirects('/users');

        $entityManager->clear();
    }

    public function testUserEdit(): void
    {
        $client = static::createClient();

        $admin = new User();
        $admin->setUsername('editadmin' . uniqid());
        $admin->setEmail('editadmin' . uniqid() . '@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $admin->setPassword($hasher->hashPassword($admin, 'password'));

        $userToEdit = new User();
        $userToEdit->setUsername('usertoedit' . uniqid());
        $userToEdit->setEmail('usertoedit' . uniqid() . '@example.com');
        $userToEdit->setPassword($hasher->hashPassword($userToEdit, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($admin);
        $entityManager->persist($userToEdit);
        $entityManager->flush();

        $client->loginUser($admin);

        $crawler = $client->request('GET', '/users/' . $userToEdit->getId() . '/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'editeduser' . uniqid();
        $form['user[email]'] = 'edited' . uniqid() . '@example.com';
        $form['user[password]'] = 'newpassword';
        $form['user[roles]'] = ['ROLE_ADMIN'];

        $client->submit($form);
        $this->assertResponseRedirects('/users');

        $entityManager->clear();
    }

    public function testUserCreateRequiresAdmin(): void
    {
        $client = static::createClient();

        $user = new User();
        $user->setUsername('regularuser' . uniqid());
        $user->setEmail('regular' . uniqid() . '@example.com');
        $hasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        $entityManager->clear();
    }
}