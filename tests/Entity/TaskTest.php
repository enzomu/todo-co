<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskCreation(): void
    {
        $task = new Task();

        $this->assertInstanceOf(Task::class, $task);
        $this->assertFalse($task->isDone());
        $this->assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
    }

    public function testSetTitle(): void
    {
        $task = new Task();
        $task->setTitle('Test Title');

        $this->assertEquals('Test Title', $task->getTitle());
    }

    public function testSetContent(): void
    {
        $task = new Task();
        $task->setContent('Test Content');

        $this->assertEquals('Test Content', $task->getContent());
    }

    public function testSetIsDone(): void
    {
        $task = new Task();
        $task->setIsDone(true);

        $this->assertTrue($task->isDone());
    }

    public function testToggle(): void
    {
        $task = new Task();

        $task->toggle(true);
        $this->assertTrue($task->isDone());

        $task->toggle(false);
        $this->assertFalse($task->isDone());
    }

    public function testSetCreatedAt(): void
    {
        $task = new Task();
        $date = new \DateTimeImmutable('2023-01-01');
        $task->setCreatedAt($date);

        $this->assertEquals($date, $task->getCreatedAt());
    }

    public function testAuthorManagement(): void
    {
        $task = new Task();
        $user = new User();
        $user->setUsername('testuser');

        $task->setAuthor($user);

        $this->assertEquals($user, $task->getAuthor());
        $this->assertEquals($user, $task->getUser());

        $task->setUser($user);
        $this->assertEquals($user, $task->getAuthor());
    }

    public function testToString(): void
    {
        $task = new Task();
        $task->setTitle('Test Task');

        $this->assertEquals('Test Task', (string) $task);
    }
}