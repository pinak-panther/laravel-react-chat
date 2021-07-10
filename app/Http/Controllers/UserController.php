<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class UserController extends Controller
{
    private $validateProfile = [
        'name'=>'required',
        'contact_no'=>'required',
        'profile_pic' => 'sometimes|required|image',

    ];
    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    /**
     * Fetch all the users from DB
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUser()
    {
        $allUsers = $this->userRepo->getAllUser();
        return $this->sendResponse($allUsers, "All users send successfully");
    }

    /**
     * Fetch currently logged in user from DB
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUer()
    {
        $authUser = $this->userRepo->getAuthUser();
        return $this->sendResponse($authUser, "Currently Authenticated User");
    }

    /**
     * Redirect a user to profile page.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Fetch profile data for currently logged in user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfileData()
    {
        $profile = $this->userRepo->getAuthUser();
        return $this->sendResponse($profile, "Profile Data for logged in user.");
    }

    /**
     * Update profile data for logged in user.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfileData(Request $request)
    {
        $request->validate($this->validateProfile);
        if(!$this->userRepo->updateProfile($request)){
            return $this->sendError('Something Went wrong while updating data');
        }
        return $this->sendSuccess("Profile Data Updated.");
    }
}
