<?php


namespace App\Repository;

use App\Models\Application;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Update profile data for logged in user.
     * @param $inputs
     * @return mixed
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if($request->hasFile('profile_pic'))
        {
            $uploadedFile = $request->file('profile_pic');
            $fileName = $user->id.'_profile_pic.'.$uploadedFile->getClientOriginalExtension();
            Storage::putFileAs("public/profile_pics",$uploadedFile,$fileName);
            return  $user->update([
                'name'=>$request->get('name'),
                'contact_no'=>$request->get('contact_no'),
                'profile_pic'=>$fileName
            ]);
        }
        return  $user->update([
            'name'=>$request->get('name'),
            'contact_no'=>$request->get('contact_no'),
        ]);
    }

}
