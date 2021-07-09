<?php


namespace App\Repository;


use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Return all users.
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllUser();

    /**
     * Return currently authenticated user.
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getAuthUser();
}
