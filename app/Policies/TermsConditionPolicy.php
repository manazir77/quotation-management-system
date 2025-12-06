<?php

namespace App\Policies;

use App\Models\TermsCondition;
use App\Models\User;

class TermsConditionPolicy
{
    public function view(User $user, TermsCondition $term): bool
    {
        return $user->id === $term->user_id;
    }

    public function update(User $user, TermsCondition $term): bool
    {
        return $user->id === $term->user_id;
    }

    public function delete(User $user, TermsCondition $term): bool
    {
        return $user->id === $term->user_id;
    }
}

