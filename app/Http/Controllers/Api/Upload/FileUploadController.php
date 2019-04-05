<?php


namespace App\Http\Controllers\Api\Upload;


use App\Http\Controllers\Controller;
use App\Models\QueuedReport;
use App\Models\UploadedFile;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileUploadController extends Controller
{

    public function postUpload(Request $request, FileUploadService $service)
    {
        $fieldname = $request->get('fieldname', 'file');
        $upload = $service->saveFile($request->file($fieldname));

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

    public function getQueuedReportFile($report_id)
    {
        $report = QueuedReport::find($report_id);
        $path = $report->file_path;
        $fileName = $report->file_name;
        $response = new BinaryFileResponse($path);
        $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName, sha1($fileName));
        return $response;
    }
}
