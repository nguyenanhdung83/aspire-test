<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email);
}
