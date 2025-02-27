<?php

namespace App\Jobs;

use App\Mail\ForgetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email, public string $code)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new ForgetPassword($this->code));
    }
}
