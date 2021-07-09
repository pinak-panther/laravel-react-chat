<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;

class UserController extends Controller
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function getAllUser(){
       $allUsers = $this->userRepo->getAllUser();
       return $this->sendResponse($allUsers,"All users send successfully");
   }

   public function getAuthUer(){
       $authUser =$this->userRepo->getAuthUser();
       return $this->sendResponse($authUser,"Currently Authenticated User");
   }
}
