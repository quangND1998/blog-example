<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\Client\SerieTransformer;
use App\Repositories\SerieRepository;
use App\Models\Serie;

class SerieController extends ApiController
{
    protected $serie;

    /**
     * Constructor
     */
    public function __construct(SerieRepository $serieRepository)
    {
        $this->serie = $serieRepository;
    }

    /**
     * Get serie list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $name = $request->query('name');
        $series = $this->serie->getListByCondition($name);

        return $this->response()
            ->attach($series, new SerieTransformer, ['owner', 'coverImage'])
            ->success();
    }

    /**
     * Get single serie detail
     *
     * @var App\Models\Serie $series
     *
     * @return json
     */
    public function show(Serie $series)
    {
        return $this->response()
            ->attach($series->load(['owner', 'coverImage']), new SerieTransformer, ['owner', 'coverImage'])
            ->success();
    }
}
