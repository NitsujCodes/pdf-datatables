<?php

namespace NitsujCodes\PDFDataTable\Contracts;

use NitsujCodes\PDFDataTable\Exceptions\ColumnIndexOutOfBoundsException;
use NitsujCodes\PDFDataTable\PDFDataTable;
use TCPDF;

interface TableBuilderInterface
{
    /**
     * Create a new instance of the Table Builder
     *
     * @return self
     */
    public function newBuilder(): self;

    /**
     * Set the TCPDF instance
     *
     * @param TCPDF $pdf
     * @return self
     */
    public function withPDF(TCPDF $pdf): self;

    /**
     * Add a column to the table
     *
     * @param ColumnDefinitionInterface $column
     * @return self
     */
    public function addColumn(ColumnDefinitionInterface $column): self;

    /**
     * Add multiple columns to the table
     * @param array $columns
     * @return self
     */
    public function addColumns(array $columns): self;

    /**
     * Clear the tables columns
     * @return self
     */
    public function clearColumns(): self;

    /**
     * Clear all columns and replace with the given new ones
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns): self;

    /**
     * Finalize and then load into the main classes table cache and return the main class
     * @return PDFDataTable
     */
    public function build(): PDFDataTable;

    /**
     * Get the current table instance
     * @return TableInterface
     */
    public function getTable(): TableInterface;

    /**
     * Get the current attached TCPDF instance
     * @return TCPDF
     */
    public function getPDF(): TCPDF;

    /**
     * Get all columns
     * @return array
     */
    public function getColumns(): array;

    /**
     * Return how many columns there are
     * @return int
     */
    public function getColumnCount(): int;

    /**
     * Get the column at the given index
     * @param int $index
     * @return ColumnDefinitionInterface
     * @throws ColumnIndexOutOfBoundsException
     */
    public function getColumn(int $index): ColumnDefinitionInterface;
}