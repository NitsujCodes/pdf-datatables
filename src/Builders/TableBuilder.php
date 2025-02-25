<?php

namespace NitsujCodes\PDFDataTable\Builders;

use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;
use NitsujCodes\PDFDataTable\Contracts\TableBuilderInterface;
use NitsujCodes\PDFDataTable\Contracts\TableInterface;
use NitsujCodes\PDFDataTable\Entities\Tables\Table;
use NitsujCodes\PDFDataTable\Factories\TableFactory;
use NitsujCodes\PDFDataTable\PDFDataTable;
use NitsujCodes\PDFDataTable\Services\TableService;
use TCPDF;

class TableBuilder implements TableBuilderInterface
{
    protected array $columns;

    public function __construct(
        protected TCPDF $pdf,
    ) {
        $this->columns = [];
    }

    /**
     * @inheritDoc
     */
    public function newBuilder(): TableBuilderInterface
    {
        return new static($this->pdf);
    }

    /**
     * @inheritDoc
     */
    public function withPDF(TCPDF $pdf): TableBuilderInterface
    {
        $this->pdf = $pdf;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addColumn(ColumnDefinitionInterface $column): TableBuilderInterface
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addColumns(array $columns): TableBuilderInterface
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * @inheritDoc
     */
    public function clearColumns(): TableBuilderInterface
    {
        $this->columns = [];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setColumns(array $columns): TableBuilderInterface
    {
        $this->clearColumns();
        $this->addColumns($columns);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): TableInterface
    {
        return TableFactory::simple($this->pdf)->addColumns($this->columns);
    }

    /**
     * @inheritDoc
     */
    public function getTable(): TableInterface
    {
        // TODO: Implement getTable() method.
    }

    /**
     * @inheritDoc
     */
    public function getPDF(): TCPDF
    {
        // TODO: Implement getPDF() method.
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        // TODO: Implement getColumns() method.
    }

    /**
     * @inheritDoc
     */
    public function getColumnCount(): int
    {
        // TODO: Implement getColumnCount() method.
    }

    /**
     * @inheritDoc
     */
    public function getColumn(int $index): ColumnDefinitionInterface
    {
        // TODO: Implement getColumn() method.
    }
}