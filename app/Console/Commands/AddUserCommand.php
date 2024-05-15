<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:add-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new login user to the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->ask('Please provide a name for the user');
        $email = $this->ask('Please provide an email address for the user');
        $password = $this->secret('Now please provide a password');

        foreach (compact('name', 'email', 'password') as $prop => $value) {
            if (empty($value)) {
                $this->error("Provided {$prop} value is empty");

                return 1;
            }
        }

        $user = User::query()->where('email', '=', $email)->first();
        if (!is_null($user)) {
            $update = $this->confirm('User with that email already exists, Do you want to update them?');
            if (!$update) {
                $this->error('Taking no action');

                return 1;
            }
        } else {
            $user = new User();
        }

        $user->fill([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);
        $user->save();

        $this->info("User created, You can now login with the email {$email} and your provided password.");

        return 0;
    }
}
