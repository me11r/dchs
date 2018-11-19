<?php

namespace App\Services;


use Illuminate\Http\UploadedFile;

class FileUploadService
{
    /**
     * @param UploadedFile $file
     * @return \App\Models\UploadedFile
     */
    public function saveFile(UploadedFile $file): \App\Models\UploadedFile
    {
        $filename = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $path = $file->store($this->formStorageFilename($filename));

        $upload = new \App\Models\UploadedFile([
            'filename' => $filename,
            'filepath' => $path,
            'mime' => $mime,
            'size' => $file->getSize(),
        ]);

        $upload->save();

        return $upload;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function formStorageFilename(string $filename): string
    {
        $hash = sha1($filename);
        $prefix = '/upload/' . substr($hash, 4, 2) . '/' . substr($hash, 8, 2) . '/';
        return $prefix . $hash;
    }
}
