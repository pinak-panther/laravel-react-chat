<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repository\MessageRepositoryInterface;
class MessageController extends Controller
{
    private $validateMessage = [
        'message'=>'required',
        'selectedUser'=>'required'
    ];
    /**
     * @var MessageRepositoryInterface
     */
    private $messageRepo;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepo = $messageRepository;
    }

    /**
     * Store a newly created message in Database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->validateMessage);
        $inputs = $request->only(['message','selectedUser']);
        $newMessage = $this->messageRepo->store($inputs);
        broadcast(new MessageSent($newMessage))->toOthers();
        return $this->sendResponse($newMessage,'message send successfully');
    }

    /**
     * Get all messages for a user.
     * @param $user_to
     * @return JsonResponse
     */
    public function allMessages($user_to){
        $allMessages = $this->messageRepo->allMessages($user_to);
        return $this->sendResponse($allMessages,'All messages for current logged in user and selected users fetched successfully');
    }

    /**
     * Get all unread messages for a user.
     * @param $user
     * @return JsonResponse
     */
    public function getUnreadMessage($user){
        $unReadMessages = $this->messageRepo->unreadMessages($user);
        return $this->sendResponse($unReadMessages,'All unread messages for given user.');
    }

    /**
     * Change the status of given message to read.
     * @param Message $message
     * @return JsonResponse
     */
    public function updateMessageStatus(Message $message){
        $updatedMessage = $this->messageRepo->updateMessageStatus($message);
        return $this->sendResponse($updatedMessage,'Status for give message changed to read successfully');
    }

    /**
     * Change the status of all messages for a user to read.
     * @param int
     * @return JsonResponse
     */
    public function changeMessageStatusForUser($user_id){
        $updatedMessage = $this->messageRepo->changeMessageStatusForUser($user_id);
        return $this->sendResponse($updatedMessage,'Status for all messages have been changed for user');
    }

}
