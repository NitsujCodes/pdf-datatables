<?php

namespace NitsujCodes\PDFDataTable\Services;

use Exception;
use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Row;
use NitsujCodes\PDFDataTable\DTO\RowConfig;
use NitsujCodes\PDFDataTable\DTO\Table;
use NitsujCodes\PDFDataTable\DTO\TableConfig;
use NitsujCodes\PDFDataTable\PDFDataTables;

class TableService
{
    private int $tableCount;
    private array $tables;

    public function __construct()
    {
        $this->tableCount = 0;
        $this->tables = [];
    }

    public function &create(
        string $name, TableConfig $config, ?RowConfig $defaultRowConfig = null, ?ColumnConfig $defaultColumnConfig = null
    ) : Table {
        if (array_key_exists($name, $this->tables))
            throw new Exception("Table with name $name already exists");

        $this->tables[$name] = new Table(
            uniqueName: $name,
            config: $config,
            defaultRowConfig: $defaultRowConfig ?? new RowConfig(),
            defaultColumnConfig: $defaultColumnConfig ?? new ColumnConfig(),
        );
        $this->tableCount++;
        return $this->tables[$name];
    }

    /**
     * @param string $name
     * @return Table
     * @throws Exception
     */
    public function &getTable(string $name) : Table
    {
        if (!array_key_exists($name, $this->tables))
            throw new Exception("Table with name $name does not exist");

        return $this->tables[$name];
    }

    /**
     * @param string $tableName
     * @param array $header
     * @return void
     * @throws Exception
     */
    public function setRowHeader(string $tableName, array $header) : void
    {
        $table = $this->getTable($tableName);
        $newHeaderRow = PDFDataTables::getInstance()->rowService->create(
            config: $table->headerRow?->config ?? new RowConfig(),
            rowColumnsData: $header,
            columnType: ColumnType::HeaderColumn,
            defaultColumnConfig: $table->headerRow?->defaultColumnConfig ?? new ColumnConfig(),
        );
        $this->tables[$tableName] = $table->updateProperty('headerRow', $newHeaderRow);
    }

    /**
     * @param string $tableName
     * @param array $rowData
     * @return void
     * @throws Exception
     */
    public function addRow(string $tableName, array $rowData) : void
    {
        $table = $this->getTable($tableName);
        $dataRows = $table->dataRows;
        $dataRows[] = PDFDataTables::getInstance()->rowService->create(
            config: $table->defaultRowConfig,
            rowColumnsData: $rowData,
            defaultColumnConfig: $table->defaultColumnConfig,
        );
        $this->tables[$tableName] = $table->updateProperty('dataRows', $dataRows);
        $this->tableCount++;
    }

    /**
     * @param string $tableName
     * @param int $rowIndex
     * @return void
     * @throws Exception
     */
    public function removeRow(string $tableName, int $rowIndex) : void
    {
        $table = $this->getTable($tableName);

        if (!isset($table->dataRows[$rowIndex]))
            throw new Exception("Row $rowIndex does not exist");

        $dataRows = $table->dataRows;
        unset($dataRows[$rowIndex]);
        $this->tables[$tableName] = $table->updateProperty('dataRows', $dataRows);
        $this->tableCount--;
    }
}