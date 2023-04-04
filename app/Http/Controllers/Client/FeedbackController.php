<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Feedback;
use App\Http\Requests\Feedbacks\CreateFeedbackRequest;
use App\Jobs\CreateFeedbackNotifyJob;

class FeedbackController extends ApiController
{
    /**
     * Create feedback
     *
     * @var App\Http\Requests\Feedbacks\CreateFeedbackRequest $request
     *
     * @return json
     */
    public function store(CreateFeedbackRequest $request)
    {
        $data = $request->only(['email', 'content']);
        $feedback = Feedback::create($data);
        dispatch(new CreateFeedbackNotifyJob($feedback));

        return $this->response()
            ->success();
    }
}
