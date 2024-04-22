<?php

namespace App\Imports;

use App\Enums\RowKeysType;
use App\DTO\ProductDataDto;
use App\Services\ImportService;
use App\DTO\ProductOffsetDataDto;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class ImportCatalog implements ToCollection, WithHeadingRow
{
    /**
     * The Excel worksheet instance.
     */
    protected Worksheet $worksheet;

    /**
     * Counter for new records added to the database.
     */
    private int $newRecords = 0;

    /**
     * Counter for duplicate records that were not added.
     */
    private int $duplicates = 0;

    /**
     * Constructor loads the Excel spreadsheet.
     */
    public function __construct(string $filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $this->worksheet = $spreadsheet->getActiveSheet();
    }

    /**
     * Processes each collection of rows from the Excel file.
     * @param Collection $collection The collection of rows from the Excel file.
     */
    public function collection(Collection $collection): void
    {
        $importService = resolve(ImportService::class);
        foreach ($collection as $index => $row) {
            // Compensates for the heading row in the worksheet. Data starts from the second row, hence index + 2.
            $currentRowNumber = $index + 2;
            $columnAValue = $this->worksheet->getCell('A' . $currentRowNumber)->getValue();

            // Retrieves additional info from column index 10 if present; used to check for shifted data entries.
            $additionalInfo = $row[10] ?? null;//todo comment

            $type = $columnAValue ?? $row[RowKeysType::TYPE->value] ?? $row[RowKeysType::CATEGORY_TITLE->value];
            $categoryKey = $additionalInfo ? $row[RowKeysType::MANUFACTURER->value] : $row[RowKeysType::CATEGORY_TITLE->value];
            $category = $importService->getCategory($categoryKey);

            if (!empty($additionalInfo)) {
                $dto = ProductOffsetDataDto::fromArray($row->toArray(), $type, $category, $additionalInfo);

                $product = $importService->importOffsetData($dto);
            } else {
                $dto = ProductDataDto::fromArray($row->toArray(), $type, $category);

                $product = $importService->importData($dto);
            }

            if ($product->wasRecentlyCreated) {
                $this->newRecords++;
            } else {
                $this->duplicates++;
            }
        }

        logger("Import completed: {$this->newRecords} new records added, {$this->duplicates} duplicates found.");
    }
}
