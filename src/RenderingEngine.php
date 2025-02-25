<?php
namespace NitsujCodes\PDFDataTable;

use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;
use NitsujCodes\PDFDataTable\Contracts\TableInterface;
use TCPDF;

class RenderingEngine
{
    private CssMappingRegistry $cssRegistry;

    public function __construct(
            protected TCPDF $pdf
    ) {
        $this->cssRegistry = new CssMappingRegistry();
    }

    public function renderTable(TableInterface $table, array $tableData): void
    {
        $columns = $table->getColumns();
        $this->renderHeaderRow($columns);
        foreach ($tableData as $row) {
            $this->renderRow($row, $columns);
        }
        // TODO: Table rendering with base styles
    }

    public function renderHeaderRow(array $columns): void
    {
        // TODO: Render row with styles
        foreach ($columns as $column) {
            $this->renderHeaderColumn($column);
        }
    }

    public function renderHeaderColumn(ColumnDefinitionInterface $column): void
    {
        // TODO: Render header column
    }

    public function renderRow(array $rowData, array $columns): void
    {
        foreach ($columns as $column) {
            $this->renderColumn($column, $rowData);
        }
        // TODO: Row rendering
    }

    public function renderColumn(ColumnDefinitionInterface $column, array $rowData): void
    {
        // TODO: Render row column with styles
    }

    private static function cssMeasurementToMm(string $cssMeasurement, TCPDF $pdfContext): float
    {
        preg_match('/(\d+)(px|rem|%|em|pt)/', $cssMeasurement, $matches);
        $number = (float)$matches[1];
        $unit = $matches[2];
        $currentFontSizeMm = $pdfContext->getFontSize(); // This returns points

        return match ($unit) {
            'px' => $number * 0.264583333,
            'pt' => $number * 0.352777778,
            'rem' => $number * 16 * 0.264583333, // TODO: make rem lookup font-size from table styling
            'em' => $number * $currentFontSizeMm,
            '%' => ($number / 100) * $pdfContext->getPageWidth(),
            default => 0,
        };
    }
}