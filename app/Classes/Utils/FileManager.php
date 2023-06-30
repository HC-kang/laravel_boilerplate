<?php

namespace App\Classes\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class FileManager
{
    const THUMBNAIL_EXTENSION = 'png';
    private UploadedFile $uploadedFile;
    private string $extension;
    private array $nameArr;
    private string $basePath;
    private string $uniqueName;
    private string $uniqueThumbnailName;

    public function __construct(UploadedFile $uploadedFile, string $basePath)
    {
        $this->uploadedFile = $uploadedFile;
        $this->basePath = $basePath;

        $nameArr = explode('.', $uploadedFile->getClientOriginalName());
        $this->extension = array_pop($nameArr);
        $this->nameArr = $nameArr;
        $this->uniqueName = $this->generateUniqueName();
        $this->uniqueThumbnailName = $this->generateUniqueThumbnailName();
    }

    public function generateUniqueName(): string
    {
        return Carbon::now()->getTimestamp() . '_' . uniqid() . '.' . $this->extension;
    }
    
    public function getFileOriginName()
    {
        return implode('', $this->nameArr) . '.' . $this->extension;
    }

    public function generateUniqueThumbnailName(): string
    {
        return Carbon::now()->getTimestamp() . '_' . uniqid() . '.' . FileManager::THUMBNAIL_EXTENSION;
    }

    public function getFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getFileClientMimeType(): string
    {
        return $this->uploadedFile->getClientMimeType();
    }

    public function getFileSize()
    {
        return $this->uploadedFile->getSize();
    }

    public function getFilePath($suffix = null): string
    {
        return $this->getFolderPath() . '/' . $this->uniqueName . $suffix;
    }

    public function getFolderPath(): string
    {
        return sprintf("%s/%s/%s", $this->basePath, Carbon::now()->year, Carbon::now()->month);
    }

    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    public function getImageDimension(): array
    {
        return getimagesize($this->uploadedFile);
    }

    public function saveFile(): void
    {
        $this->uploadedFile->move($this->getFolderPath(), $this->uniqueName);
    }
}
