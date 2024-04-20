<?php

namespace App\Console\Commands;

use ZipArchive;
use App\Rules\FileValidation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

final class DownloadFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for download and extract specific xlsx file, then delete the zip archive.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $url = config('services.dropbox.file');
        $zipPath = storage_path('app/public/catalog_for_test.zip');
        $extractPath = storage_path('app/public');
        $fileValidation = new FileValidation(5120);

        $response = Http::get($url);
        if ($response->successful()) {
            file_put_contents($zipPath, $response->body());
            $this->info('ZIP file downloaded and saved successfully.');

            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $this->info('Inspecting files inside the zip:');
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $filePath = $extractPath . '/' . $filename;

                    $validationPassed = true;
                    $fileValidation->validate('file', $filePath, function ($message) use (&$validationPassed) {
                        $this->error($message);
                        $validationPassed = false;
                    });

                    if ($validationPassed) {
                        $zip->extractTo($extractPath, $filename);
                        $this->info('Extracted: ' . $filename);
                    }
                }
                $zip->close();
                unlink($zipPath);
                $this->info('All required files extracted successfully to ' . $extractPath . ' ZIP file deleted successfully.');
            } else {
                $this->error('Failed to open ZIP file.');
            }
        } else {
            $this->error('Failed to download the file. Status code: ' . $response->status());
        }
    }
}
