<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\Post;
use App\Models\Notification;
use App\Events\NotifyNewBookmark;

class CreateBookmarkNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'resource' => $this->post->hash_id,
            'content' => $this->getMessage(),
            'users_id' => $this->post->users_id,
            'type' => 'comment',
        ];
        $notification = Notification::create($data);
        event(new NotifyNewBookmark($notification, $this->post->users_id));
    }

    /**
     * Construct message for notification
     *
     * @return string
     */
    private function getMessage()
    {
        return '<strong>' . $this->user->username . '</strong> '
            . __('vừa lưu bài viết')
            . ' <strong>' . $this->post->title . '</strong>';
    }
}
