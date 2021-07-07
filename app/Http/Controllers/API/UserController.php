<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
   public function getAllUser(){
       return $this->sendResponse(User::all(),"All users send successfully");
   }

}
