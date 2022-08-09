<?php

namespace CloudMonitor\Azure\Events;

use Illuminate\Foundation\Events\Dispatchable;

class LoginSuccess
{
    use Dispatchable;

    /**
     * Newly logged in user.
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get newly logged in user.
     * 
     * @return Registration
     */
    public function user()
    {
        return $this->user;
    }
}