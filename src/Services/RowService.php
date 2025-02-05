<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Row;
use NitsujCodes\PDFDataTable\DTO\RowConfig;
use NitsujCodes\PDFDataTable\PDFDataTables;

class RowService
{
    public function __construct() {}

    /**
     * @param RowConfig $config
     * @param array $rowColumnsData
     * @param ColumnConfig $defaultColumnConfig
     * @return Row
     */
    public function create(RowConfig $config, array $rowColumnsData, ColumnConfig $defaultColumnConfig): Row
    {
        $columns = [];
        foreach ($rowColumnsData as $reference => $content) {
            $columns[] = ColumnService::create(
                reference: $reference,
                config: $columnData['config'] ?? new ColumnConfig(),
                content: $columnData['content'] ?? '',
                columnType: $columnData['type'] ?? ColumnType::RowColumn,
            );
        }

        return new Row(
            config: $config,
            defaultColumnConfig: $defaultColumnConfig,
            columns: $columns,
        );
    }
}