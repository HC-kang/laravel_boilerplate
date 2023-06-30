<?php

namespace App\Repositories\Eloquent;

use App\Models\UploadedImage;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UploadedImageRepositoryInterface;
use App\Traits\ApiResponseTraits;

class UploadedImageRepository extends BaseRepository implements UploadedImageRepositoryInterface
{
    use ApiResponseTraits;

    /**
     * Summary of model
     * @var 
     */
    protected $model;

    /**
     * Summary of __construct
     * @param \App\Models\UploadedImage $model
     */
    public function __construct(
        UploadedImage $model,
    ) {
        parent::__construct($model);
        $this->model = $model;
    }
}