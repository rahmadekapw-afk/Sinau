<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reset:admin-password {email : Email of the admin user} {--password=secret : New password}';

    /**
     * The console command description.
     */
    protected $description = 'Reset the password of an admin user to a known bcrypt hashed password';

    public function handle()
    {
        $email = $this->argument('email');
        $newPassword = $this->option('password');

        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        $this->info("Password for {$email} has been reset to '{$newPassword}'.");
        return 0;
    }
}
