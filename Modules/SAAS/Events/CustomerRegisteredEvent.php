<?php

namespace Modules\SAAS\Events;


use App\Models\User;
use Illuminate\Queue\SerializesModels;

class CustomerRegisteredEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public User $user,
    ) {
        // dd($user);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
