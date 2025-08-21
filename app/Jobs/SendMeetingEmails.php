<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingScheduledMail;
class SendMeetingEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emails;
    public $details;

    public function __construct(array $emails, array $details)
    {
        $this->emails = $emails;
        $this->details = $details;
    }

    public function handle()
    {
        foreach ($this->emails as $email) {
            Mail::to($email)->send(new MeetingScheduledMail($this->details));
        }
    }


}
