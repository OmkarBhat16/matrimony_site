<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PromoteUserToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:promote-user-to-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds user by email and promotes them to profile_manager role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $user = \App\Models\User::where('email', $this->ask('Enter the email of the user to promote to profile manager'))->first();
        if (!$user) {
            $this->error('User not found');
            return;
        }
        $user->update([
            'role' => 'profile_manager',
        ]);
        $this->info('User promoted to profile manager successfully');
    }
}
