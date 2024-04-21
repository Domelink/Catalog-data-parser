<?php

namespace tests\Unit;

use Tests\TestCase;
use App\Rules\FileValidation;

final class FileValidationTest extends TestCase
{
    public function testItFailsForMaxFileSize()
    {
        $testFilePath = 'temporaryfile.txt';
        $testFileSize = 1024 * 1024 + 1;
        file_put_contents($testFilePath, str_repeat('a', $testFileSize));

        $rule = new FileValidation(1024);
        $failureMessage = '';

        $rule->validate('testFile', $testFilePath, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file exceeds the maximum allowed size of 1024 KB.', $failureMessage);

        unlink($testFilePath);
    }

    public function testItFailsForNonXlsxFiles()
    {
        $rule = new FileValidation(5);

        $testFilename = __DIR__ . '/test_file.txt';
        file_put_contents($testFilename, 'Some content');

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        finfo_close($fileInfo);

        $failureMessage = '';
        $rule->validate('file', $testFilename, function ($message) use (&$failureMessage) {
            $failureMessage = $message;
        });

        $this->assertEquals('The file must be a valid .xlsx file.', $failureMessage);
        unlink($testFilename);
    }
}
