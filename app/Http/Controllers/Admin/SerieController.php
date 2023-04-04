<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Serie;
use App\Transformers\Admin\SerieTransformer;
use App\Http\Requests\Series\CreateSerieRequest;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Series\UpdateSerieRequest;
use App\Repositories\SerieRepository;

class SerieController extends ApiController
{
    protected $post;

    /**
     * Constructor
     */
    public function __construct(
        PostRepository $postRepository,
        SerieRepository $serieRepository
    ) {
        $this->serie = $serieRepository;
        $this->post = $postRepository;
    }

    /**
     * Get my serie list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $name = $request->query('name');
        $series = $this->serie->getMyListByCondition(auth()->user()->id, $name);

        return $this->response()
            ->attach($series, new SerieTransformer, ['owner', 'coverImage'])
            ->success();
    }

    /**
     * Get my serie detail
     *
     * @var App\Models\Serie $series
     *
     * @return json
     */
    public function show(Serie $series)
    {
        $this->authorize('modify', $series);

        return $this->response()
            ->attach($series->load(['owner', 'coverImage']), new SerieTransformer, ['owner', 'coverImage'])
            ->success();
    }

    /**
     * Create new series
     *
     * @var App\Http\Requests\Series\CreateSerieRequest $request
     *
     * @return json
     */
    public function store(CreateSerieRequest $request)
    {
        $this->authorize('create', Serie::class);
        $data = $request->only(['name', 'slug', 'description', 'images_id']);
        $serie = Serie::create($data);

        return $this->response()
            ->attach($serie->load(['owner', 'coverImage']), new SerieTransformer, ['owner', 'coverImage'])
            ->created(__('Tạo serie thành công'));
    }

    /**
     * Update given serie
     *
     * @var App\Models\Serie $series
     * @var App\Http\Requests\Series\UpdateSerieRequest $request
     *
     * @return json
     */
    public function update(Serie $series, UpdateSerieRequest $request)
    {
        $this->authorize('modify', $series);
        $data = $request->only(['name', 'slug', 'description', 'images_id']);
        $series->update($data);

        return $this->response()
            ->attach($series->load(['owner', 'coverImage']), new SerieTransformer, ['owner', 'coverImage'])
            ->success(__('Cập nhật serie thành công'));
    }

    /**
     * Delete given serie
     *
     * @var App\Models\Serie $series
     *
     * @return none
     */
    public function destroy(Serie $series)
    {
        $this->authorize('modify', $series);
        $result = DB::transaction(function () use ($series) {
            $this->post->removeSerie($series->id);
            $series->delete();

            return true;
        });

        if ($result) {
            return $this->response()
                ->deleted();
        }

        return $this->response()
            ->fail();
    }
}
