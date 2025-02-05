<?php

ini_set('memory_limit', '4G');
ini_set('max_execution_time', 300);

require __DIR__ . '/../vendor/autoload.php';

use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\RowConfig;
use NitsujCodes\PDFDataTable\DTO\TestObject;
use NitsujCodes\PDFDataTable\PDFDataTables;
use NitsujCodes\PDFDataTable\Services\HydrationService;
use NitsujCodes\PDFDataTable\Services\RowService;

// Initialize the table generator
$pdf = new TCPDF();
// Define table headers and rows
$headers = ['Name', 'Age', 'Email'];
$headerRow = PDFDataTables::$rowService->create(
    config: new RowConfig(),
    rowColumnsData: $headers,
    defaultColumnConfig: new ColumnConfig()
);

$processedRows = [];
$rows = [
    ['name' => 'Alice', 'age' => 25, 'email' => 'alice@example.com', 'phone' => '123-456-7890'],
    ['name' => 'Bob', 'age' => 30, 'email' => 'bob@example.com', 'phone' => '987-654-3210'],
    ['name' => 'Charlie', 'age' => 35, 'email' => 'charlie@example.com', 'phone' => '654-321-0987'],
    ['name' => 'Diana', 'age' => 28, 'email' => 'diana@example.com', 'phone' => '321-654-7890'],
    ['name' => 'Ethan', 'age' => 32, 'email' => 'ethan@example.com', 'phone' => '789-123-4560'],
    ['name' => 'Fiona', 'age' => 27, 'email' => 'fiona@example.com', 'phone' => '456-789-1230'],
    ['name' => 'George', 'age' => 40, 'email' => 'george@example.com', 'phone' => '123-789-6540'],
    ['name' => 'Hannah', 'age' => 22, 'email' => 'hannah@example.com', 'phone' => '987-123-6540'],
    ['name' => 'Ian', 'age' => 26, 'email' => 'ian@example.com', 'phone' => '654-987-1230'],
    ['name' => 'Jenna', 'age' => 31, 'email' => 'jenna@example.com', 'phone' => '123-987-6540'],
    ['name' => 'Kevin', 'age' => 29, 'email' => 'kevin@example.com', 'phone' => '321-123-7890'],
    ['name' => 'Laura', 'age' => 34, 'email' => 'laura@example.com', 'phone' => '456-321-7890'],
    ['name' => 'Michael', 'age' => 45, 'email' => 'michael@example.com', 'phone' => '789-456-1230'],
    ['name' => 'Nina', 'age' => 20, 'email' => 'nina@example.com', 'phone' => '987-654-1230'],
    ['name' => 'Oscar', 'age' => 38, 'email' => 'oscar@example.com', 'phone' => '321-456-7890'],
    ['name' => 'Paula', 'age' => 24, 'email' => 'paula@example.com', 'phone' => '456-789-6540'],
    ['name' => 'Quentin', 'age' => 37, 'email' => 'quentin@example.com', 'phone' => '123-456-3210'],
    ['name' => 'Rachel', 'age' => 29, 'email' => 'rachel@example.com', 'phone' => '654-789-1230'],
    ['name' => 'Steve', 'age' => 41, 'email' => 'steve@example.com', 'phone' => '789-123-6540'],
    ['name' => 'Tina', 'age' => 23, 'email' => 'tina@example.com', 'phone' => '987-321-4560'],
];

foreach ($rows as $row) {
    $processedRows[] = PDFDataTables::$rowService->create(
        config: new RowConfig(),
        rowColumnsData: $row,
        defaultColumnConfig: new ColumnConfig()
    );
}

echo "<pre>";
print_r($processedRows);
die();

// Add the table to the PDF
$pdf->addTable($headers, $rows);

// Save the PDF
$pdf->save(__DIR__ . '/example.pdf');

echo "PDF generated successfully!\n";