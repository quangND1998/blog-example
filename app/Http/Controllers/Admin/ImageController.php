<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Images\UploadImageRequest;
use App\Models\Image;
use App\Repositories\ImageRepository;
use App\Transformers\Admin\ImageTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class ImageController extends ApiController
{
    protected $image;
    protected $allowFilter = [
        'type',
        'from',
        'to',
    ];

    /**
     * Constructor
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->image = $imageRepository;
    }

    /**
     * Display my image list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $condition = $this->constructFilter($request);
        $images = $this->image->getMyList(auth()->user()->id, $condition);

        return $this->response()
            ->attach($images, new ImageTransformer)
            ->success();
    }

    /**
     * Store upload image
     *
     * @var  App\Http\Requests\Images\UploadImageRequest $request
     *
     * @return json
     */
    public function store(UploadImageRequest $request)
    {
        $this->authorize('create', Image::class);
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $imageFile = $request->file('img');
            $imageInfo = $this->extractImageInfo($imageFile);
            $image = DB::transaction(function () use ($imageInfo, $request) {
                $image = Image::create($imageInfo);
                $imageName = $imageInfo['uuid'] . '.' . $imageInfo['extension'];
                $request->img->storeAs('images', $imageName, 'default');

                return $image;
            });

            return $this->response()
                ->attach($image, new ImageTransformer, ['owner'])
                ->created(__('Upload ảnh thành công'));
        } else {
            return $this->json()->fail(__('Xảy ra lỗi khi upload ảnh'));
        }
    }

    /**
     * Extract image info from upload file
     *
     * @var Illuminate\Http\UploadedFile $imageFile
     * @var string $this
     *
     * @return array
     */
    private function extractImageInfo($imageFile)
    {
        return [
            'uuid' => (string) Uuid::generate(4),
            'origin_name' => $imageFile->getClientOriginalName(),
            'extension' => substr($imageFile->getMimeType(), 6),
        ];
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
        if (isset($filter['from'])) {
            array_push($condition, ['created_at', '>', $filter['from']]);
        }
        if (isset($filter['to']) && isset($filter['from'])) {
            array_push($condition, ['created_at', '<', $filter['to']]);
        }

        return $condition;
    }
}
