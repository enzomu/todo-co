<?php

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'Test Task',
            'content' => 'Test Content',
        ];

        $task = new Task();
        $form = $this->factory->create(TaskType::class, $task);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertEquals('Test Content', $task->getContent());
    }
}