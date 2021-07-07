<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * @var User
     */
    private $toUser;
    /**
     * @var User
     */
    private $fromUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,User $toUser, User $fromUser)
    {
        //
        $this->message = $message;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//           return new Channel('my-channel');
             $channelPrefix = $this->fromUser->id > $this->toUser->id ?   $this->toUser->id."_".$this->fromUser->id :$this->fromUser->id."_".$this->toUser->id ;
             Log::info("Private Channel Name");
             Log::debug("{$channelPrefix}_private_channel");
             return new Channel("{$channelPrefix}_private_channel");
    }
}


