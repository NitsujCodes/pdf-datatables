<?php

namespace NitsujCodes\PDFDataTable;

use NitsujCodes\PDFDataTable\DTO\Column;
use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Row;
use NitsujCodes\PDFDataTable\DTO\RowConfig;
use NitsujCodes\PDFDataTable\DTO\Table;
use NitsujCodes\PDFDataTable\DTO\TableConfig;
use NitsujCodes\PDFDataTable\Services\ColumnService;
use NitsujCodes\PDFDataTable\Services\HydrationService;
use NitsujCodes\PDFDataTable\Services\RowService;
use NitsujCodes\PDFDataTable\Services\TableService;
use TCPDF;
use Exception;

class PDFDataTables
{
    public readonly HydrationService $hydrationService;
    public readonly TableService $tableService;
    public readonly RowService $rowService;
    public readonly ColumnService $columnService;

    private static ?PDFDataTables $instance = null;

    private TCPDF $pdf;
    private TableConfig $tableConfig;
    private array $tables;
    private int $tableCount;

    private ?Table $currentTable;
    private ?Row $currentRow;
    private ?Column $currentColumn;

    /**
     * @throws Exception
     */
    public function __construct(?TCPDF &$pdf = null)
    {
        // Services
        $this->hydrationService = new HydrationService();
        $this->tableService = new TableService();
        $this->rowService = new RowService();
        $this->columnService = new ColumnService();

        if (!is_null($pdf)) {
            $this->pdf = &$pdf;
        } else {
            $this->pdf = new TCPDF();
        }

        $this->tableConfig = $this->getDefaultTableConfig();
        $this->tableCount = 0;
        $this->tables = [];
    }

    public function attachTPCDF(TCPDF &$pdf): void
    {
        $this->pdf = &$pdf;
    }

    public function initTCPDF(
        $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskcache = false,
        $pdfa = false
    ): void
    {
        $this->pdf = new TCPDF(
            $orientation,
            $unit,
            $format,
            $unicode,
            $encoding,
            $diskcache,
            $pdfa,
        );
    }

    public static function getInstance() : PDFDataTables
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDFDataTables();
        }

        return self::$instance;
    }

    /**
     * @throws Exception
     */
    private function getDefaultTableConfig(): TableConfig
    {
        return new TableConfig([
            // TODO: Setup defaults
        ]);
    }

    /**
     * @throws Exception
     */
    private function configureTable(string $tableUnique, TableConfig|array $tableConfig): void
    {
        if (!array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique does not exist");

        if (!$tableConfig instanceof TableConfig)
            throw new Exception("Table config must be an instance of TableConfig or an array");

        $this->updateTableProp($tableUnique, 'config', $tableConfig);
    }

    private function getTableConfig(): TableConfig
    {
        return $this->tableConfig;
    }

    /**
     * Add a table to the PDF
     *
     * @param string $tableUnique
     * @param TableConfig $tableConfig
     * @param array $rowHeader
     * @param array $rows Table data (array of arrays)
     * @throws Exception
     */
    public function addTable(string $tableUnique, TableConfig $tableConfig, array $rowHeader = [], array $rows = []): void
    {
        if (array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique already exists");

        $defaultRowConfig = new RowConfig();
        $defaultColumnConfig = new ColumnConfig();

        $headerRow = $this->rowService->create(
            config: $defaultRowConfig,
            rowColumnsData: $rowHeader,
            columnType: ColumnType::HeaderColumn,
            defaultColumnConfig: $defaultColumnConfig,
        );

        $this->tables[$tableUnique] = new Table(
            $tableUnique,
            config: $tableConfig,
            defaultRowConfig: $defaultRowConfig,
            defaultColumnConfig: $defaultColumnConfig,
            dataRows: $rows,
            headerRow: $headerRow,
        );
        $this->tableCount++;
    }

    public function usingTable(string $tableUnique): self
    {
        if (!array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique does not exist");
        $this->resetCurrentRow();
        $this->currentTable =& $this->tables[$tableUnique];
        return $this;
    }

    public function inRow(int $rowIndex): self
    {
        if (is_null($this->currentTable))
            throw new Exception("No table has been selected");
        if (!isset($this->currentTable->rows[$rowIndex]))
            throw new Exception("Row $rowIndex does not exist");
        $this->resetCurrentColumn();
        $this->currentRow =& $this->currentTable->rows[$rowIndex];
        return $this;
    }

    public function inColumn(int $columnIndex): self
    {
        if (is_null($this->currentTable))
            throw new Exception("No table has been selected");
        if (!isset($this->currentTable->columns[$columnIndex]))
            throw new Exception("Column $columnIndex does not exist");
        $this->currentColumn =& $this->currentTable->columns[$columnIndex];
        return $this;
    }

    private function resetCurrentColumn(): void
    {
        $this->currentColumn = null;
    }

    public function resetCurrentRow(): void
    {
        $this->currentRow = null;
        $this->resetCurrentColumn();
    }

    public function resetCurrentTable(): void
    {
        $this->currentTable = null;
        $this->resetCurrentRow();
    }

    /**
     * @param string $tableUnique
     * @return void
     * @throws Exception
     */
    public function removeTable(string $tableUnique): void
    {
        if (!array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique does not exist");
    }

    /**
     * @param string $tableUnique
     * @return void
     */
    public function renderTable(string $tableUnique): void
    {
        // TODO: Render logic
    }

    /**
     * Save the PDF to the specified file
     * 
     * @param string $filename
     */
    public function save(string $filename): void
    {
        $this->pdf->Output($filename, 'F');
    }

    /**
     * @param string $tableUnique
     * @param string $propName
     * @param TableConfig $tableConfig
     * @return void
     * @throws Exception
     */
    private function updateTableProp(string $tableUnique, string $propName, TableConfig $tableConfig): void
    {
        if (!array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique does not exist");

        if (!property_exists($this->tables[$tableUnique], $propName))
            throw new Exception("Table with unique name $tableUnique does not have a property $propName");

        $this->tables[$tableUnique]->$propName = $tableConfig;
    }
}