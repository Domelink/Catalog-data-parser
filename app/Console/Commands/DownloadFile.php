<?php

namespace App\Console\Commands;

use App\Rules\FileValidation;
use Illuminate\Console\Command;
use App\Jobs\ParseDataToCatalogJob;
use App\Interfaces\HttpServiceInterface;
use App\Interfaces\ZipArchiveServiceInterface;

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
