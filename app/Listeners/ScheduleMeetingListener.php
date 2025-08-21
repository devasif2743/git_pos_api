<?php

namespace App\Listeners;

use App\Events\MeetingScheduled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Jobs\SendMeetingEmails;
class ScheduleMeetingListener
{
    /**
     * Create the event listener.
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
     * @param  \App\Events\MeetingScheduled  $event
     * @return void
     */
    public function handle(MeetingScheduled $event)
    {
         SendMeetingEmails::dispatch($event->emails, $event->details);
    }
}
