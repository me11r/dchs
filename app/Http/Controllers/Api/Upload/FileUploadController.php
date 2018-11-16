<?php


namespace App\Http\Controllers\Api\Upload;


use App\Http\Controllers\Controller;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileUploadController extends Controller
{
    public function formStorageFilename(string $filename) {
        $hash = sha1($filename);
        $prefix = '/upload/'.substr($hash, 4, 2) . '/' . substr($hash,8, 2). '/';
        return $prefix . $hash;
    }

    public function postUpload(Request $request)
    {
        $fieldname = $request->get('fieldname', 'file');
        $file =  $request->file($fieldname);
        $filename = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $path = $file->store($this->formStorageFilename($filename));
        $upload = new UploadedFile([
            'filename' => $filename,
            'filepath' => $path,
            'mime' => $mime,
            'size' => $file->getSize(),
        ]);
        $upload->save();

        return response()->json($upload);
    }

    public function getFileInfo(Request $request, $file_id)
    {
        $upload = UploadedFile::findOrFail($file_id);
        return response()->json([
            'name' => $upload->filename,
            'size' => $upload->size,
            'mime' => $upload->mime
        ]);
    }

    public function getFile(Request $request, $file_id)
    {
        $upload = UploadedFile::findOrFail($file_id);
        $path = \Storage::path($upload->filepath);
        $response = new BinaryFileResponse($path);
        $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $upload->filename, sha1($upload->filename));
        return $response;
    }
}
