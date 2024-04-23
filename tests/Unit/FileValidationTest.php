<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\FileValidation;

final class FileValidationTest extends TestCase
{
    public function testItFailsForMaxFileSize()
    {
        $testFilename = 'temporaryfile.xlsx';
        $testFileSize = 1024 * 1024 * 5 + 1;

        $rule = new FileValidation(5120);
        $failureMessage = '';

        $rule->validate($testFilename, $testFileSize, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file exceeds the maximum allowed size of 5120 KB.', $failureMessage);
    }

    public function testItFailsForNonXlsxFiles()
    {
        $testFilename = 'test_file.txt';
        $testFileSize = 1024 * 10;

        $rule = new FileValidation(5120);
        $failureMessage = '';

        $rule->validate($testFilename, $testFileSize, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file must be a valid .xlsx file.', $failureMessage);
    }
}
