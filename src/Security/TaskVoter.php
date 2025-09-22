<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';
    public const TOGGLE = 'TOGGLE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::TOGGLE])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        return match ($attribute) {
            self::EDIT, self::TOGGLE => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => false,
        };
    }

    private function canEdit(Task $task, User $user): bool
    {
        if ($task->belongsTo($user)) {
            return true;
        }

        return $user->hasRole('ROLE_ADMIN');
    }

    private function canDelete(Task $task, User $user): bool
    {
        if ($task->belongsTo($user)) {
            return true;
        }

        if ($user->hasRole('ROLE_ADMIN') && $this->isAnonymousTask($task)) {
            return true;
        }

        return false;
    }

    private function isAnonymousTask(Task $task): bool
    {
        return $task->getAuthor() && $task->getAuthor()->getUsername() === 'anonyme';
    }
}