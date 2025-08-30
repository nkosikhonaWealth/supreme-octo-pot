<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignYouthRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $youthRole = Role::firstOrCreate([
            'name' => 'youth',
            'guard_name' => 'web'
        ]);
    
        $event->user->assignRole($youthRole);
    }
}
