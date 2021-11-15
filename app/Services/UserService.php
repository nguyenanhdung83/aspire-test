<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Hash;
use App\Services\BaseService;

class UserService extends BaseService
{
    public function __construct(UserInterface $repository)
    {
        parent::__construct($repository);
    }

    public function createToken(User $user)
    {
        $token = $user->createToken($user->email);

        return $token->plainTextToken;
    }
}
