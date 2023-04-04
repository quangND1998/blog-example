<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Feedback;
use App\Models\Notification;
use App\Events\NotifyNewFeedback;

class CreateFeedbackNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'resource' => 'none',
            'content' => __('Bạn mới nhận được một phản hồi'),
            'users_id' => env('APP_ADMIN_ID'),
            'type' => 'feedback',
        ];
        $notification = Notification::create($data);
        event(new NotifyNewFeedback($notification));
    }
}
