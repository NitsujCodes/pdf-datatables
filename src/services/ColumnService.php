<?php

namespace NitsujCodes\PDFDataTable\services;

use NitsujCodes\PDFDataTable\dto\Column;
use NitsujCodes\PDFDataTable\dto\ColumnConfig;
use NitsujCodes\PDFDataTable\PDFDataTables;

use ReflectionException;

class ColumnService
{
    public function __construct()
    {

    }

    /**
     * @throws ReflectionException
     */
    public function updateColumnProperty(Column $column, $property, $value): Column
    {
        $newData = $column->toArray();
        $newData[$property] = $value;
        return PDFDataTables::$hydrationService->hydrate(Column::class, $newData);
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