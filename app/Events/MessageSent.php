<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        //
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->message->user_to==0){
            $channelName = 'public-channel';
            Log::info("Public Channel Name");
            Log::debug($channelName);
            return new Channel($channelName);
        }
        $channelName = "App.Models.User.{$this->message->user_to}";
        Log::info("Private Channel Name");
        Log::debug($channelName);
//        return new PrivateChannel($channelName);


        $presenceChannel = "App.Models.User.Presence{$this->message->user_to}";
        Log::info("Presence Channel Name");
        Log::debug($presenceChannel);
        return [new PresenceChannel($presenceChannel),new PrivateChannel($channelName)];


    }
}


