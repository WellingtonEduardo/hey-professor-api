<?php

namespace App\Policies;

use App\Models\{Question, User};

class QuestionPolicy
{
    public function update(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }

    public function forceDelete(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }

    public function archive(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }

    public function restore(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }

    public function publish(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }

}
