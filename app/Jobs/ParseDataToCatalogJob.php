<?php

namespace App\Jobs;

use App\Imports\ImportCatalog;
use Maatwebsite\Excel\Facades\Excel;

final class ParseDataToCatalogJob extends BaseJob
{
    /**
     * Path to the Excel file.
     */
    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        Excel::import(new ImportCatalog($this->filePath), $this->filePath);

        $executionTime = microtime(true) - $startTime;
        logger("Execution time: {$executionTime} seconds");
    }
}

