<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadedImage\UploadedImageRequest;
use App\Services\UploadedImageService;
use App\Traits\ApiResponseTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UploadedImageController extends Controller
{
    use ApiResponseTraits;

    private UploadedImageService $uploadedImageService;

    public function __construct(
        UploadedImageService $uploadedImageService
    ) {
        $this->uploadedImageService = $uploadedImageService;
    }

    public function index(): JsonResponse
    {
        $result = $this->uploadedImageService->index();
        return $this->successResponse($result);
    }

    public function store(UploadedImageRequest $request): JsonResponse
    {
        $uploadedImageRequest = $request->toUploadedImageStoreDto();
        $result = $this->uploadedImageService->store($uploadedImageRequest, Auth::id());
        return $this->successResponse($result);
    }

    public function destroy(int $uploadedImageId): JsonResponse
    {
        $result = $this->uploadedImageService->destroy($uploadedImageId);
        return $this->successResponse($result);
    }
}
