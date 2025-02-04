<?php

require __DIR__ . '/../vendor/autoload.php';

use NitsujCodes\PDFDataTable\PDFDataTables;

//// Initialize the table generator
//$pdf = new PDFDataTables([
//    'creator' => 'My DataTable Generator',
//    'author' => 'Your Name',
//    'title' => 'My Generated Table',
//]);
//
//// Define table headers and rows
//$headers = ['Name', 'Age', 'Email'];
//$rows = [
//    ['John Doe', 30, 'john@example.com'],
//    ['Jane Smith', 25, 'jane@example.com'],
//    ['Sam Green', 35, 'sam@example.com'],
//];
//
//// Add the table to the PDF
//$pdf->addTable($headers, $rows);
//
//// Save the PDF
//$pdf->save(__DIR__ . '/example.pdf');
//
//echo "PDF generated successfully!\n";