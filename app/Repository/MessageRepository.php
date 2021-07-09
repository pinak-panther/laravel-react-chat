<?php


namespace App\Repository;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * Create a message.
     * @param $inputs
     * @return mixed
     */
    public function store($inputs){
        $message = $inputs['message'];
        $toUser = $inputs['selectedUser'];
        $newMessage = Message::create([
            'user_from' => Auth::user()->id,
            'user_to' =>  $toUser ,
            'message' => $message
        ]);
        return $newMessage;
    }

    /**
     * Fetch all records for a user
     * @param $user_to
     * @return mixed
     */
    public function allMessages($user_to){
        $allMessages1 = Message::where('user_to',$user_to)->where('user_from',auth()->user()->id)->get();
        $allMessages2 = Message::where('user_from',$user_to)->where('user_to',auth()->user()->id)->get();
        return $allMessages1->concat($allMessages2)->sortBy('created_at')->values()->all();
    }


    /**
     * Get unread messages for a user.
     * @param $user
     * @return mixed
     */
    public function unreadMessages($user)
    {
        return Message::where('user_to',$user)->where('status','0')->count();
    }

    /**
     * Update a message status to read
     * @param Message $message
     * @return mixed
     */
    public function updateMessageStatus(Message $message)
    {
        return $message->update(['status'=>1]);
    }

    /**
     * Change the status of all messages for a user to read.
     * @param User $user
     * @return mixed
     */
    public function changeMessageStatusForUser($user_id)
    {
        $user= User::find($user_id);
        $query = Message::where('user_from',$user->id)->where('user_to',auth()->user()->id);
        $query->update(['status'=>1]);
        return $query->get();
    }
}
