<?php

namespace App\Console\Commands;

use App\Rules\FileValidation;
use App\Services\HttpService;
use Illuminate\Console\Command;
use App\Services\ZipArchiveService;
use App\Jobs\ParseDataToCatalogJob;

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

    private const maxFileSize = 5120;

    public function __construct(private readonly HttpService $httpService, private readonly ZipArchiveService $zipArchiveService)
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
        $zipPath = storage_path('app/public/catalog_for_test.zip');
        $extractPath = storage_path('app/public');
        $fileValidation = new FileValidation(self::maxFileSize);

        if ($this->httpService->downloadFile($url, $zipPath)) {
            $filePath = $this->zipArchiveService->processZip($zipPath, $extractPath, $fileValidation);
            ParseDataToCatalogJob::dispatch($filePath);
            $this->info('File extracted successfully, started to dispatch job.');
        } else {
            $this->error('Failed to download ZIP file.');
        }
    }
}
