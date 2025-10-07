<?php

namespace App\Jobs;

use App\Mail\ApplicationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ApplicationEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $applicationData,
        public ?array $user = null,
        public array $files = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = config('mail.admin_address', config('mail.from.address'));

        if (!$recipient) {
            return;
        }
        $mailable = new ApplicationMail($this->applicationData, $this->user, $this->files);

        Mail::to($recipient)->send($mailable);
    }
}
