<?php

namespace App\Services\Repository\Service;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\AuthenticationException;
use App\Services\Repository\Contracts\UserRepositoryInterface;

class AuthService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepo;

    private $data;


    /**
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }



    /**
     * @param mixed $data
     *
     * @return [type]
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }



    /**
     * @return array
     */
    public function register()
    {
        $user = $this->userRepo->create($this->data);
        if (auth()->validate(['email' => $this->data['email'], 'password' => $this->data['password']])) {
            return [
                'user' => $user,
                'authorization' => $this->generateToken($user)
            ];
        }
    }




    /**
     * login
     * @return array
     * @throws AuthenticationException
     */
    public function login()
    {
        $credential = auth()->validate(['email' => $this->data['email'], 'password' => $this->data['password']]);

        if ($credential) {
            $user = auth()->getProvider()->retrieveByCredentials(['email' => $this->data['email']]);
            return [
                'user' => $user,
                'authorization' => $this->generateToken($user)
            ];
        }

        throw new AuthenticationException('Invalid credentials');
    }
    /**
     * @param User $user
     *
     * @return [type]
     */
    private function generateToken(User $user)
    {
        return  ['type' => 'bearer', 'token' => JWTAuth::fromUser($user)];
    }
}
