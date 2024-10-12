<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Responses\ResponsesInterface;
use App\Models\User;
use App\Services\Repository\Service\AuthService;

class AuthenticationController extends Controller
{
    /**
     * @var ResponsesInterface
     */
    protected $responder;
    /**
     * @var AuthService
     */
    private $auth;

    /**
     * @param ResponsesInterface $responder
     * @param AuthService $auth
     */
    public function __construct(ResponsesInterface $responder, AuthService $auth)
    {
        $this->responder = $responder;
        $this->auth = $auth;
    }

    /**
     * @param RegistrationRequest $request
     *
     * @return [type]
     */
    public function register(RegistrationRequest $request)
    {
        try {
            $user = $this->auth->setData($request->validated())->register();
        } catch (\Throwable $th) {
           throw $th;
        }
        return $this->responder->respondCreated("Hello {$request->name} you have registered successfully!",  $user);
    }


    /**
     * @param LoginRequest $request
     *
     * @return [type]
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->auth->setData($request->validated())->login();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->responder->respond(['message' => 'you have logged in successfully', 'data' => $user]);
    }
}
