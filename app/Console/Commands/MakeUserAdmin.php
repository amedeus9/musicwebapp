<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email? : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Enter the user email');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        if ($user->is_admin) {
            $this->info("User '{$user->name}' ({$user->email}) is already an admin!");
            return 0;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("User '{$user->name}' ({$user->email}) is now an admin!");
        return 0;
    }
}
