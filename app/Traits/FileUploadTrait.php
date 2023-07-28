<?php
namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    /**
     * Uploads a file from a request and saves it to the specified destination directory.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request object containing the file.
     * @param string $fileInputName The name of the file input field in the form.
     * @param string $destinationDirectory The directory where the file should be saved.
     * @param string|null $fileName The desired file name (optional). If not provided, the original file name will be used.
     *
     * @return string|null The uploaded file's name if successful, or null if no file was uploaded.
     */
    public function uploadFile($request, $fileInputName, $destinationDirectory, $fileName = null)
    {
        if ($request->hasFile($fileInputName)) {
            $file = $request->file($fileInputName);

            $directoryPath = public_path($destinationDirectory);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }

            $destinationPath = public_path($destinationDirectory);
            $fileName = $fileName ?? $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);

            return $fileName;
        }

        return null;
    }
}
