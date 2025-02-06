<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ImageStorageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class FileUploadController extends Controller
{

    use ImageStorageTrait;
    
    /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;

    public function uploadFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:2048000', // max file size in KB
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        

        if ($request->file('file')->isValid()) {

            // $folder = 'uploads/stl'; 
            // $path = $this->storeImage($request->file('file'), $folder);


            $startTime = microtime(true);
            // Validate and store the file
            $request->validate([
                'file' => 'required|file', // 10 MB max
            ]);

            $file = $request->file('file');
            $file->store('uploads');

            $endTime = microtime(true);
            $uploadTime = $endTime - $startTime;

            // Get the file size in megabytes
            $fileSize = $file->getSize() / 1024 / 1024;

            // Calculate upload speed in Mbps
            $uploadSpeed = ($fileSize / $uploadTime) * 8;

            $data = [
                'uploadTime' => $uploadTime,
                'uploadSpeed' => $uploadSpeed,
                'fileSize' => $fileSize,
            ];
            // $path = $request->file('file')->store('uploads', 'public');

            $this->response['message'] = 'File upload successfully!';
            $this->response['data'] = $data;
            $this->response['status'] = $this->status;
            return response()->json($this->response, $this->status);
                    }

        $this->response['message'] = 'Failed to upload file!';
        $this->response['data'] = [];
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
        
    }


    public function uploadChunk(Request $request)
    {


        $fileName = $request->input('fileName');
        $chunkIndex = $request->input('currentChunk');
        $totalChunks = $request->input('totalChunks');

        $tempDir = storage_path('uploads/stl/temp/' . md5($fileName));

        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }

        $chunkFile = $request->file('file');
        $chunkFile->move($tempDir, $chunkIndex);

        return response()->json(['status' => 'Chunk ' . $chunkIndex . ' uploaded']);
    }

    public function finalizeUpload(Request $request)
    {
        $fileName = $request->input('fileName');
        $totalChunks = $request->input('totalChunks');

        $tempDir = 'uploads/stl';

        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }
        $tempDir = storage_path('uploads/stl/temp/' . md5($fileName));
        $finalPath = storage_path('uploads/stl/' . $fileName);

        $finalFile = fopen($finalPath, 'ab');

        for ($i = 1; $i <= $totalChunks; $i++) {
            $chunkPath = $tempDir . '/' . $i;
            $chunkFile = fopen($chunkPath, 'rb');
            stream_copy_to_stream($chunkFile, $finalFile);
            fclose($chunkFile);

            // Delete the chunk after appending
            unlink($chunkPath);
        }

        fclose($finalFile);

        // Optionally, remove the temp directory
        File::deleteDirectory($tempDir);

        return response()->json(['message' => 'File uploaded successfully']);
    }

    
    
    // public function uploadChunk(Request $request)
    // {
    //     $filename = $request->input('filename');
    //     $totalChunks = (int) $request->input('totalChunks');
    //     $currentChunk = (int) $request->input('currentChunk');
        
    //         // Temporary directory to store chunks
    //         $tempDir = storage_path('app/uploads/temp');
    //         if (!File::exists($tempDir)) {
    //             File::makeDirectory($tempDir, 0755, true);
    //         }

    //         // Store the chunk
    //         $chunkPath = $tempDir . '/' . $filename . '.part' . $currentChunk;
    //         if(!empty($totalChunks) && !empty($currentChunk)){
    //             $request->file('file')->move($tempDir, $chunkPath);
    //         }

    //         $finalPath = storage_path('app/uploads/final/' . $filename);
    //         if (!File::exists($finalPath)) {
    //             File::makeDirectory($finalPath, 0777, true);
    //         }
            

    //         // Assemble the chunks when all are uploaded
    //         if ($currentChunk === $totalChunks) {
    //             $finalFile = fopen($finalPath, 'a');

    //             $finalFilePath = storage_path('app/uploads/') . $filename;
 
    //             for ($i = 1; $i <= $totalChunks; $i++) {
    //                 // $chunk = fopen($chunkFile, 'r');

    //                 $chunk = fopen($tempDir . '/' . $filename . '.part' . $i, 'r');
    //                 stream_copy_to_stream($chunk, $finalFile);
    //                 fclose($chunk);
    //                 unlink($tempDir . '/' . $filename . '.part' . $i); // Remove chunk
    //             }

    //             fclose($finalFile);

    //             // Clean up temporary directory
    //             File::deleteDirectory($tempDir);

    //             return response()->json(['status' => 'completed', 'filename' => $filename]);
    //         }
    

    //     return response()->json(['status' => 'chunk_uploaded']);
    // }

    // public function uploadChunk(Request $request)
    // {
    //     $fileName = $request->fileName;
    //     $chunkIndex = $request->index;
    //     $totalChunks = $request->totalChunks;

    //     $chunk = $request->file('file');
    //     $chunkDir = storage_path('app/uploads/chunks/' . $fileName);
        
    //     if (!File::exists($chunkDir)) {
    //         File::makeDirectory($chunkDir, 0777, true);
    //     }

    //     $chunk->move($chunkDir, $fileName . '.part' . $chunkIndex);

    //     return response()->json(['status' => 'Chunk ' . $chunkIndex . ' of ' . $totalChunks . ' uploaded']);
    // }


    // public function finalizeUpload(Request $request)
    // {
    //     $fileName = $request->fileName;
    //     $chunkDir = storage_path('app/uploads/chunks/' . $fileName);
    //     $finalPath = storage_path('app/uploads/final/' . $fileName);

    //     if (!File::exists($finalPath)) {
    //         File::makeDirectory($finalPath, 0777, true);
    //     }

    //     $finalFile = fopen($finalPath, 'a');

    //     foreach (glob($chunkDir . '/' . $fileName . '.part*') as $chunkFile) {
    //         $chunk = fopen($chunkFile, 'r');
    //         fwrite($finalFile, fread($chunk, filesize($chunkFile)));
    //         fclose($chunk);
    //     }

    //     fclose($finalFile);

    //     // Clean up the chunks
    //     File::deleteDirectory($chunkDir);

    //     return response()->json(['status' => 'File uploaded and chunks removed']);
    // }

    public function createDirecrotory(Request $request)
    {
        $path = storage_path('app/upload/');

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }   
    }

}
