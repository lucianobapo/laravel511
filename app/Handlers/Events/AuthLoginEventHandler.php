<?php

namespace App\Handlers\Events;

use App\Models\User;
use App\Repositories\MessagesRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthLoginEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  User $user
     * @param  $remember
     * @return void
     */
    public function handle(User $user, $remember)
    {
        MessagesRepository::sendUserLogin([
            'name'=>config('mail.from')['name'],
            'email'=>config('mail.from')['address'],
            'user'=>$user,
        ]);
//        dd("login fired and handled by class with User instance and remember variable");
    }
}
