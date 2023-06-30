<?php

namespace App\Dtos\UploadedImage;

use Illuminate\Http\UploadedFile;

class UploadedImageStoreDto
{
    private ?int $productId;
    private int $type;
    private UploadedFile $uploadedImage;

    public function __construct(
        ?int $productId,
        int $type,
        UploadedFile $uploadedImage
    ) {
        $this->productId = $productId;
        $this->type = $type;
        $this->uploadedImage = $uploadedImage;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getUploadedImage(): UploadedFile
    {
        return $this->uploadedImage;
    }
}
