<?php

namespace NitsujCodes\PDFDataTable\Services;

use Exception;
use NitsujCodes\PDFDataTable\DTO\Column;
use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;

use ReflectionException;

class ColumnService
{
    public function __construct()
    {

    }

    /**
     * @param int|string $reference
     * @param ColumnConfig $config
     * @param string $content
     * @param ColumnType $columnType
     * @return Column
     */
    public static function create(
        int|string $reference, ColumnConfig $config, string $content, ColumnType $columnType = ColumnType::RowColumn
    ) : Column {
        return new Column(
            reference: $reference,
            config: $config,
            content: $content,
            type: $columnType,
        );
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateColumnProperty(Column $column, $property, $value): Column
    {
        $newData = $column->toArray();
        $newData[$property] = $value;
        return Column::fromArray($newData);
    }

    /**
     * @throws ReflectionException
     */
    public function updateColumnContent(Column $column, $content): Column
    {
        return $this->updateColumnProperty($column, 'content', $content);
    }

    /**
     * @throws ReflectionException
     */
    public function updateColumnConfig(Column $column, ColumnConfig $config): Column
    {
        return $this->updateColumnProperty($column, 'config', $config);
    }

}