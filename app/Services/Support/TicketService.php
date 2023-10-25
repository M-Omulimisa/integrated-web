<?php


namespace App\Services\Support;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TicketService
{

    const UPLOAD_PATH = "public/uploads/tickets";

    public function __construct() { }
    
    public function getResolutionByPriority($priorityId)
    {
        return Carbon::now()->addDays(15);
    }

    public function generateTrackingNumber($length=null)
    {
        return 'T'.date('ymd').rand(1000, 9999);
    }


    /**
     * Upload file and return file name or false.
     * @param string $file_key The key in the request
     * @return false|string
     */
    public static function doUpload(string $file_key)
    {
        $path = request()->file($file_key)->store(self::UPLOAD_PATH);

        if (! Storage::exists($path)) {
            return false;
        }

        return File::basename($path);
    }

    /**Get resolved file url
     * @param string $file_name File name on disk.
     * @return string
     */
    public static function fileUrl(string $file_name): string
    {
        return asset('storage/uploads/tickets/'.$file_name);
    }

    /**
     * Delete file
     * @param string $file_name
     * @return bool
     */
    public static function deleteFile(string $file_name): bool
    {
        return Storage::delete(self::UPLOAD_PATH . '/' . $file_name);
    }
}
