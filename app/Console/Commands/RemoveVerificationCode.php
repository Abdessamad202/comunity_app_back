<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RemoveVerificationCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-verification-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        User::each(function ($user) {
            $deleted = $user->forgetPasswordProcess()
                ->where('created_at', '<', Carbon::now()->subMinutes(2))
                ->delete();

            if ($deleted) {
                $this->info("Deleted expired codes for User ID: {$user->id}");
            }
        });

        $this->info('Expired codes deletion completed.');

    }
}
