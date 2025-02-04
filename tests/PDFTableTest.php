<?php

namespace YourNamespace\PDFDataTable\Tests;

use PHPUnit\Framework\TestCase;
use YourNamespace\PDFDataTable\PDFTable;

class PDFTableTest extends TestCase
{
    public function testTableGeneration()
    {
        $pdf = new PDFTable();
        $headers = ['Col1', 'Col2', 'Col3'];
        $rows = [
            [1, 2, 3],
            [4, 5, 6],
        ];

        $pdf->addTable($headers, $rows);

        $outputFile = __DIR__ . '/test.pdf';
        $pdf->save($outputFile);

        $this->assertFileExists($outputFile);

        // Clean up
        unlink($outputFile);
    }
}