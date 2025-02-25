<?php

namespace NitsujCodes\PDFDataTable\Contracts;

use TCPDF;

interface TableInterface
{
    // Data Inputs --
    /**
     * Render the table using data from an array
     * @param array $data
     * @return void
     */
    public function renderFromArray(array $data): void;

    /**
     * Render the table using data from a streamable resource
     * @param mixed $data
     * @return void
     */
    public function renderFromStream(mixed $data): void;

    /**
     * Render the table using data from an iterable
     * @param iterable $data
     * @return void
     */
    public function renderFromIterable(iterable $data): void;
    // -- End Data Inputs

    /**
     * Get the TCPDF instance
     * @return TCPDF
     */
    public function getPdf(): TCPDF;

    /**
     * Get all columns
     * @return array
     */
    public function getColumns(): array;

    /**
     * Get the allowed maximum width in TCPDF Units
     * Ignored if responsive mode enabled
     * @return float
     */
    public function getMaxWidth(): float;

    /**
     * Get the allowed maximum height in TCPDF Units
     * @return float
     */
    public function getMaxHeight(): float;

    /**
     * Is table in responsive width mode?
     * This will make the table ignore the maxWidth property
     * @return bool
     */
    public function isFullWidth(): bool;

    public function withColumns(array $columns): self;
    public function withColumn(ColumnDefinitionInterface $column): self;
    public function withMaxWidth(float $maxWidth): self;
    public function withMaxHeight(float $maxHeight): self;
    public function withFullWidth(bool $fullWidth): self;
    public function withPdf(TCPDF $pdf): self;
}