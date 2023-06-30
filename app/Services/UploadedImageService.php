<?php

namespace App\Services;

use App\Classes\Utils\FileManager;
use App\Dtos\UploadedImage\UploadedImageStoreDto;
use App\Repositories\UploadedImageRepositoryInterface;
use Exception;

class UploadedImageService
{
    private UploadedImageRepositoryInterface $uploadedImageRepository;

    public function __construct(
        UploadedImageRepositoryInterface $uploadedImageRepository
    ) {
        $this->uploadedImageRepository = $uploadedImageRepository;
    }

    public function index()
    {
        try {
            return [
                'data' => $this->uploadedImageRepository->all(),
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function store(UploadedImageStoreDto $uploadedImageStoreDto, int $userId)
    {
        try {
            $imageFile = new FileManager($uploadedImageStoreDto->getUploadedImage(), sprintf('images/type_%s', $uploadedImageStoreDto->getType()));
            $result = $this->uploadedImageRepository->create([
                'user_id'    => $userId,
                'product_id' => $uploadedImageStoreDto->getProductId(),
                'type'       => $uploadedImageStoreDto->getType(),
                'width'      => $imageFile->getImageDimension()[0],
                'height'     => $imageFile->getImageDimension()[1],
                'mime'       => $imageFile->getFileClientMimeType(),
                'filename'   => $imageFile->getFileOriginName(),
                'size'       => $imageFile->getFileSize(),
                'filepath'   => $imageFile->getFilePath(),
                'url'        => env('APP_URL') . $imageFile->getFilePath(),
            ]);
            $imageFile->saveFile();
            return [
                'data' => $result,
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function destroy(int $uploadedImageId)
    {
        try {
            $uploadedImage = $this->uploadedImageRepository->findById($uploadedImageId);
            $uploadedImage->delete();
            return [
                'data' => $uploadedImage,
            ];
        } catch (Exception $e) {
            return $e;
        }
    }
}
