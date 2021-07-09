<?php


namespace App\Repository;

use App\Models\Application;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Return all users with their messages
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllUser(){
        return  User::with('messagesFrom')->get();
    }

    /**
     * Return currently authenticated user.
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getAuthUser(){
        return Auth::user();
    }

}
