<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\Categories;
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
     * Cache for category instances to avoid redundant database queries.
     */
    private array $categoriesCache = [];

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
        foreach ($collection as $index => $row) {
            $currentRowNumber = $index + 2;
            $columnAValue = $this->worksheet->getCell('A' . $currentRowNumber)->getValue();

            $additionalInfo = $row[10] ?? null;

            $type = $columnAValue ?? $row['rubrika'] ?? $row['kategoriia_tovara'];
            $categoryKey = $additionalInfo ? $row['proizvoditel'] : $row['kategoriia_tovara'];

            if (!isset($this->categoriesCache[$categoryKey])) {
                $this->categoriesCache[$categoryKey] = Categories::firstOrCreate(
                    [
                        'categories' => $categoryKey
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            if (!empty($additionalInfo)) {
                $product = Products::firstOrCreate(
                    [
                        'code_of_model' => $row['opisanie_tovara']
                    ],
                    [
                        'type' => $type,
                        'name' => $row['proizvoditel'],
                        'category_id' => $this->categoriesCache[$categoryKey]->id,
                        'manufacturer' => $row['naimenovanie_tovara'],
                        'description' => $row['cena_rozn_grn'],
                        'price' => floatval($row['garantiia']),
                        'guaranty' => is_numeric($row['nalicie']) ? intval($row['nalicie']) : null,
                        'availability' => ($additionalInfo === 'есть в наличие'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            } else {
                $product = Products::firstOrCreate(
                    [
                        'code_of_model' => $row['kod_modeli_artikul_proizvoditelia']
                    ],
                    [
                        'type' => $type,
                        'name' => $row['naimenovanie_tovara'],
                        'category_id' => $this->categoriesCache[$categoryKey]->id,
                        'manufacturer' => $row['proizvoditel'],
                        'description' => $row['opisanie_tovara'],
                        'price' => floatval($row['cena_rozn_grn']),
                        'guaranty' => is_numeric($row['garantiia']) ? intval($row['garantiia']) : null,
                        'availability' => ($row['nalicie'] === 'есть в наличие'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
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
