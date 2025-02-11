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
use SplDoublyLinkedList;
use SplFixedArray;
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

    private array $tableConfigs = [];
    private array $tableRowConfigs = [];
    private array $tableRowCellConfigs = [];

    private array $tables;
    private int $tableCount;
    private array $tableRows;
    private array $tablesRowCount;
    private array $tableHeaderRows;
    private array $tableRowCells;
    private array $tableRowsCellCount;

    private ?string $currentTableName;
    private ?string $currentRowId;
    private ?string $currentCellId;

    private array $configCache;
    private array $cellIndexMap;
    private SplDoublyLinkedList $renderQueue;
    private ?SplFixedArray $currentRowCells;


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

        $this->currentTableName = null;
        $this->currentRowId = null;
        $this->currentCellId = null;

        $this->tableConfig = $this->getDefaultTableConfig();
        $this->tableCount = 0;
        $this->tables = [];

        $this->renderQueue = new SplDoublyLinkedList();
        $this->configCache = [];
        $this->cellIndexMap = [];
        $this->currentRowCells = null;
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

    public static function getInstance(): PDFDataTables
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

    /**
     * @throws Exception
     */
    public function &getCurrentTable(): Table
    {
        if (!isset($this->currentTableName))
            throw new Exception("No table has been selected");

        return $this->tables[$this->currentTableName];
    }

    /**
     * @throws Exception
     */
    public function &getCurrentRow(): Row
    {
        $this->getCurrentTable();
        if (!isset($this->currentRowId))
            throw new Exception("No row has been selected");

        if (!isset($this->tableRows[$this->currentTableName][$this->currentRowId]))
            throw new Exception(
                "Row with id $this->currentRowId does not exist in table $this->currentTableName"
            );

        return $this->tableRows[$this->currentTableName][$this->currentRowId];
    }

    /**
     * @throws Exception
     */
    public function &getCurrentCell(): Column
    {
        $this->getCurrentRow();
        if (!isset($this->currentCellId))
            throw new Exception("No column has been selected");

        if (!isset($this->tableRowCells[$this->currentTableName][$this->currentRowId][$this->currentCellId]))
            throw new Exception(
                "Column with id $this->currentCellId does not exist in row $this->currentRowId in table $this->currentTableName"
            );
        return $this->tableRowCells[$this->currentTableName][$this->currentRowId][$this->currentCellId];
    }

    /**
     * Add a table to the PDF
     *
     * @param string $tableName
     * @param float $maxWidth
     * @param float $maxHeight
     * @param int $cellsPerRow
     * @throws Exception
     */
    public function addTable(string $tableName, float $maxWidth, float $maxHeight, int $cellsPerRow, bool $selectNewTable = true): self
    {
        if (array_key_exists($tableName, $this->tables))
            throw new Exception("Table with unique name $tableName already exists");

        $this->tables[$tableName] = $this->tableService->create(
            name: $tableName,
            maxWidth: $maxWidth,
            maxHeight: $maxHeight,
            maxCellsPerRow: $cellsPerRow,
        );
        $this->tableCount++;
        $this->tablesRowCount[$tableName] = 0;

        return $selectNewTable ? $this->usingTable($tableName) : $this;
    }

    /**
     * @throws Exception
     */
    public function &getTableConfig(): TableConfig
    {
        $table = $this->getCurrentTable();
        return $this->tableConfig[$table->name];
    }

    /**
     * @throws Exception
     */
    public function &getRowConfig(): RowConfig
    {
        $row = $this->getCurrentRow();
        return $this->tableRowConfigs[$this->currentTableName][$row->getObjectId()];
    }

    /**
     * @throws Exception
     */
    public function &getCellConfig(): ColumnConfig
    {
        $cell = $this->getCurrentCell();
        return $this->tableRowCellConfigs[$this->currentTableName][$cell->getObjectId()];
    }

    public function hasRowHeader(string $tableName): bool
    {
        return !empty($this->tableHeaderRows[$tableName]);
    }

    /**
     * @throws Exception
     */
    public function addRow(array $cells, ?RowConfig $config = null, bool $moveToNewRow = true): self
    {
        $table = $this->getCurrentTable();

        if ($table->hasHeaderRow && !$this->hasRowHeader($table->name))
            throw new Exception("Table $table->name does not have a row header. This is required for adding rows.");

        $newRow = $this->rowService->create();
        $this->tableRows[$table->name][$newRow->getObjectId()] = $newRow;
        $this->tableRowCells[$table->name][$newRow->getObjectId()] = [];
        $this->tablesRowCount[$table->name]++;

        $this->tableRowConfigs[$table->name][$newRow->getObjectId()] = $config;

        return $moveToNewRow ? $this->inRow($newRow->getObjectId()) : $this;
    }

    /**
     * @param string $content
     * @param string|null $reference
     * @param ColumnConfig|null $config
     * @param bool $moveToNewCell
     * @return $this
     * @throws Exception
     */
    public function addCell(string $content, ?string $reference = null, ?ColumnConfig $config = null, bool $moveToNewCell = true): self
    {
        $row = $this->getCurrentRow();
        $table = $this->getCurrentTable();
        $tableName = $this->currentTableName;
        $rowId = $row->getObjectId();

        // Check cell count
        $currentCells = $this->tableRowCells[$tableName][$rowId] ?? [];
        $currentCellCount = count($currentCells);

        if ($currentCellCount >= $table->cellsPerRow) {
            throw new Exception("Cannot add more cells. Maximum cells per row ({$table->cellsPerRow}) reached.");
        }

        // Create new cell using Column DTO
        $newCell = new Column(
            reference: $reference ?? $rowId,
            isVisible: true,
            content: $content,
            type: $row->defaultColumnType
        );

        $cellId = $newCell->getObjectId();

        // Store the cell
        $this->tableRowCells[$tableName][$rowId][$cellId] = $newCell;
        $this->tableRowCellConfigs[$tableName][$rowId][$cellId] = $config;
        $this->tableRowsCellCount[$tableName][$rowId] = $currentCellCount + 1;

        // Index the cell
        $this->indexCell($tableName, $rowId, $cellId);

        return $moveToNewCell ? $this->inCell($cellId) : $this;
    }

    /**
     * @param array $cellsData
     * @return $this
     * @throws Exception
     */
    public function batchAddCells(array $cellsData): self
    {
        $tableName = $this->currentTableName;
        $row = $this->getCurrentRow();
        $rowId = $row->getObjectId();

        // Pre-allocate arrays
        $newCells = [];
        $newConfigs = [];

        foreach ($cellsData as $cellData) {
            $newCell = new Column(
                reference: $cellData['reference'] ?? $rowId,
                isVisible: $cellData['isVisible'] ?? true,
                content: $cellData['content'],
                type: $row->defaultColumnType
            );

            $cellId = $newCell->getObjectId();
            $newCells[$cellId] = $newCell;
            $newConfigs[$cellId] = $cellData['config'] ?? null;
        }

        // Validate total cell count
        $currentCount = $this->tableRowsCellCount[$tableName][$rowId] ?? 0;
        $newCount = $currentCount + count($newCells);
        $table = $this->getCurrentTable();

        if ($newCount > $table->cellsPerRow) {
            throw new Exception("Adding these cells would exceed maximum cells per row ({$table->cellsPerRow})");
        }

        // Bulk insert
        foreach ($newCells as $cellId => $cell) {
            $this->tableRowCells[$tableName][$rowId][$cellId] = $cell;
            $this->tableRowCellConfigs[$tableName][$rowId][$cellId] = $newConfigs[$cellId];
            $this->indexCell($tableName, $rowId, $cellId);
        }

        $this->tableRowsCellCount[$tableName][$rowId] = $newCount;

        return $this;
    }

    /**
     * @param string $tableUnique
     * @return void
     * @throws Exception
     */
    public function renderTable(string $tableUnique): void
    {
        if (!isset($this->tables[$tableUnique])) {
            throw new Exception("Table with unique name $tableUnique does not exist");
        }

        $table = $this->tables[$tableUnique];
        $pdf = $this->pdf;

        // Pre-calculate constants
        $startY = $pdf->GetY();
        $startX = $pdf->GetX();
        $cellWidth = $table->maxWidth / $table->cellsPerRow;
        $pageHeight = $pdf->getPageHeight() - 20;

        // Prepare styling parameters
        $defaultStyle = [
            'font' => $pdf->getFontFamily(),
            'size' => $pdf->getFontSize(),
            'color' => $pdf->getTextColor()
        ];

        foreach ($this->tableRows[$tableUnique] as $rowId => $row) {
            $maxRowHeight = 0;
            $currentX = $startX;
            $rowCells = $this->tableRowCells[$tableUnique][$rowId];

            // First pass - calculate row height
            foreach ($rowCells as $cell) {
                $cellHeight = $pdf->getStringHeight($cellWidth, $cell->content);
                $maxRowHeight = max($maxRowHeight, $cellHeight);
            }

            // Second pass - render cells
            foreach ($rowCells as $cellId => $cell) {
                $config = $this->tableRowCellConfigs[$tableUnique][$rowId][$cellId] ?? null;

                // Apply styling if needed
                if ($config) {
                    $this->applyCellStyle($pdf, $config);
                }

                $pdf->MultiCell(
                    $cellWidth,
                    $maxRowHeight,
                    $cell->content,
                    1,
                    'L',
                    false,
                    0
                );

                // Restore default style if needed
                if ($config) {
                    $this->restoreStyle($pdf, $defaultStyle);
                }

                $currentX += $cellWidth;
            }

            $newY = $pdf->GetY() + $maxRowHeight;

            // Check page break
            if ($newY > $pageHeight) {
                $pdf->AddPage();
                $pdf->SetXY($startX, 20);
            } else {
                $pdf->SetXY($startX, $newY);
            }
        }
    }

    private function indexCell(string $tableUnique, string $rowId, string $cellId): void
    {
        $this->cellIndexMap[$tableUnique][$rowId][$cellId] = true;
    }

    private function isCellIndexed(string $tableUnique, string $rowId, string $cellId): bool
    {
        return isset($this->cellIndexMap[$tableUnique][$rowId][$cellId]);
    }

    private function applyCellStyle(TCPDF $pdf, ColumnConfig $config): void
    {
        static $lastStyle = null;

        if ($lastStyle !== $config) {
            // Apply the configuration based on ColumnConfig properties
            // Implementation depends on what properties ColumnConfig has
            $lastStyle = $config;
        }
    }

    private function restoreStyle(TCPDF $pdf, array $style): void
    {
        $pdf->SetFont($style['font'], '', $style['size']);
        // Restore other styles as needed
    }

    public function clearTableCache(string $tableUnique): void
    {
        unset(
            $this->tableRowCells[$tableUnique],
            $this->tableRowCellConfigs[$tableUnique],
            $this->tableRowsCellCount[$tableUnique],
            $this->cellIndexMap[$tableUnique],
            $this->configCache[$tableUnique]
        );
        gc_collect_cycles();
    }

    public function renderLargeTable(string $tableUnique, int $batchSize = 100): void
    {
        if (!isset($this->tables[$tableUnique])) {
            throw new Exception("Table with unique name $tableUnique does not exist");
        }

        $rowIds = array_keys($this->tableRows[$tableUnique]);
        $batches = array_chunk($rowIds, $batchSize, true);

        foreach ($batches as $batchRows) {
            // Render table piece by piece?
            $this->clearTableCache($tableUnique);
        }
    }

public function usingTable(string $tableUnique): self
    {
        if (!array_key_exists($tableUnique, $this->tables))
            throw new Exception("Table with unique name $tableUnique does not exist");
        $this->resetCurrentRow();
        $this->currentTableName = $tableUnique;
        return $this;
    }

    public function inRow(int $rowIndex): self
    {
        if (is_null($this->currentTableName))
            throw new Exception("No table has been selected");
        if (!isset($this->currentTableName->rows[$rowIndex]))
            throw new Exception("Row $rowIndex does not exist");
        $this->resetCurrentColumn();
        $this->currentRowId =& $this->currentTableName->rows[$rowIndex];
        return $this;
    }

    /**
     * @param int $cellIndex
     * @return $this
     * @throws Exception
     */
    public function inCell(int $cellIndex): self
    {
        $row = $this->getCurrentRow();
        return $this;
    }

    private function resetCurrentColumn(): void
    {
        $this->currentCellId = null;
    }

    public function resetCurrentRow(): void
    {
        $this->currentRowId = null;
        $this->resetCurrentColumn();
    }

    public function resetCurrentTable(): void
    {
        $this->currentTableName = null;
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