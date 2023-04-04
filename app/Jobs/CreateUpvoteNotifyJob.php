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
use App\Events\NotifyNewVote;

class CreateUpvoteNotifyJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $post;
    protected $voteValue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Post $post, $voteValue)
    {
        $this->user = $user;
        $this->post = $post;
        $this->voteValue = $voteValue;
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
            'type' => 'upvote',
        ];
        $notification = Notification::create($data);
        event(new NotifyNewVote($notification, $this->post->users_id));
    }

    /**
     * Construct message for notification
     *
     * @return string
     */
    private function getMessage()
    {
        if ($this->voteValue == 1) {
            return __('Bài viết của bạn vừa được upvote')
                . ' - <strong>' . $this->post->title . '</strong>';
        }
        return __('Bài viết của bạn vừa bị down-vote')
            . ' - <strong>' . $this->post->title . '</strong>';
    }
}
