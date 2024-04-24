<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\FileSizeValidation;
use App\Rules\FileNameValidation;

final class FileValidationTest extends TestCase
{
    public function testItFailsForMaxFileSize()
    {
        $testFileSize = 1024 * 1024 * 5 + 1;

        $rule = new FileSizeValidation(5120);
        $failureMessage = '';

        $rule->validate('fileSize', $testFileSize, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file exceeds the maximum allowed size of 5120 KB.', $failureMessage);
    }

    public function testItFailsForNonXlsxFiles()
    {
        $testFilename = 'test_file.txt';

        $rule = new FileNameValidation();
        $failureMessage = '';

        $rule->validate('fileName', $testFilename, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file must be a valid .xlsx file.', $failureMessage);
    }
}
