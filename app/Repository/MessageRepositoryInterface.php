<?php


namespace App\Repository;

use \App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface MessageRepositoryInterface
{
    /**
     * Create a message.
     * @param $inputs
     * @return mixed
     */
    public function store($inputs);


    /**
     * Fetch all records for a user
     * @param $user_to
     * @return mixed
     */
    public function allMessages($user_to);

    /**
     * Get unread messages for a user.
     * @param $user
     * @return mixed
     */
    public function unreadMessages($user);

    /**
     * Update a message status to read
     * @param Message $message
     * @return mixed
     */
    public function updateMessageStatus(Message $message);

    /**
     * Change the status of all messages for a user to read.
     * @param User $user
     * @return JsonResponse
     */
    public function changeMessageStatusForUser(User $user);

}
