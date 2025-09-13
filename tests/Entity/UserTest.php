<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertCount(0, $user->getTasks());
    }

    public function testSetUsername(): void
    {
        $user = new User();
        $user->setUsername('testuser');

        $this->assertEquals('testuser', $user->getUsername());
        $this->assertEquals('testuser', $user->getUserIdentifier());
    }

    public function testSetEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testSetPassword(): void
    {
        $user = new User();
        $user->setPassword('hashedpassword');

        $this->assertEquals('hashedpassword', $user->getPassword());
    }

    public function testRolesManagement(): void
    {
        $user = new User();

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->addRole('ROLE_TEST');
        $this->assertContains('ROLE_TEST', $user->getRoles());

        $this->assertTrue($user->hasRole('ROLE_ADMIN'));
        $this->assertTrue($user->hasRole('ROLE_USER'));
        $this->assertFalse($user->hasRole('ROLE_NONEXISTENT'));
    }

    public function testTasksManagement(): void
    {
        $user = new User();
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('Test Content');

        $user->addTask($task);

        $this->assertCount(1, $user->getTasks());
        $this->assertEquals($user, $task->getAuthor());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
    }

    public function testToString(): void
    {
        $user = new User();
        $user->setUsername('testuser');

        $this->assertEquals('testuser', (string) $user);
    }
}