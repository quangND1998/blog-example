<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\FeedbackRepository;
use App\Transformers\Admin\FeedbackTransformer;
use App\Mail\ReplyFeedback;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Feedbacks\ReplyFeedbackRequest;
use App\Models\Feedback;

class FeedbackController extends ApiController
{
    protected $feedback;
    protected $allowFilter = [
        'status',
        'email',
    ];

    /**
     * Constructor
     */
    public function __construct(FeedbackRepository $feedbackRepository)
    {
        $this->feedback = $feedbackRepository;
    }

    /**
     * Get feedback list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $condition = $this->constructFilter($request);
        $feedbacks = $this->feedback->getListByCondition($condition);

        return $this->response()
            ->attach($feedbacks, new FeedbackTransformer)
            ->success();
    }

    /**
     * Reply feedback
     *
     * @var App\Models\Feedback $feedback
     * @var App\Http\Requests\Users\ReplyFeedbackRequest $request
     *
     * @return json
     */
    public function update(Feedback $feedback, ReplyFeedbackRequest $request)
    {
        $reply = $request->input('reply');
        $result = $feedback->update([
            'reply_content' => $reply,
            'status' => 'replied',
        ]);
        if ($result) {
            Mail::to($feedback->email)
                ->send(new ReplyFeedback($feedback->content, $reply));

            return $this->response()->success(__('Trả lời thành công'));
        }

        return $this->response()
            ->fail(__('Có lỗi xảy ra, vui lòng thử lại sau'));
    }

    /**
     * Delete given feedback
     *
     * @var App\Models\Feedback $feedback
     *
     * @return none
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return $this->response()
            ->deleted();
    }

    /**
     * Construct filter condition from request
     *
     * @var Illuminate\Http\Request $request
     *
     * @return array
     */
    private function constructFilter(Request $request)
    {
        $condition = [];
        $filter = $request->only($this->allowFilter);
        if (isset($filter['email'])) {
            array_push($condition, ['email', 'like', '%' . $filter['email'] . '%']);
        }
        if (isset($filter['status'])) {
            array_push($condition, ['status', '=', $filter['status']]);
        }

        return $condition;
    }
}
