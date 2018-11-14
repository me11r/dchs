<?php


namespace App\Http\Controllers\Api\Upload;


use App\Http\Controllers\Controller;
use App\Models\UploadedFile;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{

    public function postUpload(Request $request)
    {
        $fieldname = $request->get('fieldname', 'file');
        $file =  $request->file($fieldname);
        $mime = $file->getMimeType();
        $path = $file->store('upload');
        $upload = new UploadedFile([
            'filename' => $file->getFilename(),
            'filepath' => $path,
            'mime' => $mime
        ]);

        return response()->json($upload);
    }

    public function getFileInfo(Request $request, $file_id)
    {
        $upload = UploadedFile::findOrFail($file_id);
        return response()->json([
            'name' => $upload->filename,
            'size' => \Storage::size($upload->filepath),
            'mime' => $upload->mime
        ]);
    }

    public function getFile(Request $request, $file_id)
    {
        $upload = UploadedFile::findOrFail($file_id);
        return \Storage::download($upload->filepath, $upload->filename, ['Content-Type' => $upload->mime]);
    }
}
