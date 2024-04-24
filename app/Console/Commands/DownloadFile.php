<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Rules\FileNameValidation;
use App\Rules\FileSizeValidation;
use App\Jobs\ParseDataToCatalogJob;
use App\Interfaces\HttpServiceInterface;
use App\Interfaces\ZipArchiveServiceInterface;

/**
 * A Laravel console command that handles the downloading and extraction of a .zip file from a remote server.
 * This command orchestrates the process of downloading a ZIP file, extracting its contents after validation,
 * and dispatching a job to handle the extracted data. The command utilizes both an HTTP service for downloading the file
 * and a ZIP archive service for processing the ZIP file. If the download or extraction fails, appropriate messages are
 * logged using the console's output methods. This command is particularly designed to process a specific .xlsx file
 * from the extracted contents and start a job to handle this file in the system.
 *
 * Usage: php artisan file:download
 *
 * This will trigger the download of the configured file from a Dropbox link, validate and extract its contents,
 * and, if a valid .xlsx file is extracted, dispatch a job to handle the further processing of this file.
 */
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
    protected $description = 'Downloads and extracts a specific xlsx file, dispatches a job to process it, and deletes the zip archive.';

    /**
     * The maximum file size allowed for the downloaded file in kilobytes.
     *
     * @var int
     */
    private const maxFileSize = 5120;

    public function __construct(private readonly HttpServiceInterface $httpService, private readonly ZipArchiveServiceInterface $zipArchiveService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $url = config('services.dropbox.file');
        $zipPath = storage_path('app/public/catalog.zip');
        $extractPath = storage_path('app/public');
        $filenameValidation = new FileNameValidation();
        $fileSizeValidation = new FileSizeValidation(self::maxFileSize);

        if ($this->httpService->downloadFile($url, $zipPath, $fileSizeValidation)) {
            $filePath = $this->zipArchiveService->processZip($zipPath, $extractPath, $fileSizeValidation, $filenameValidation);
            if ($filePath != null) {
                ParseDataToCatalogJob::dispatch($filePath);
                $this->info('File extracted successfully, started to dispatch job.');
            } else {
                $this->error('Failed to download xlsx file.');
            }
        } else {
            $this->error('Failed to download ZIP file.');
        }
    }
}
