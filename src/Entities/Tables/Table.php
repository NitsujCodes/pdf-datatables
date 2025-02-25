<?php

namespace NitsujCodes\PDFDataTable\Entities\Tables;

use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;
use NitsujCodes\PDFDataTable\Contracts\TableInterface;
use TCPDF;

class Table implements TableInterface
{
    /**
     * @param TCPDF $pdf
     * @param ColumnDefinitionInterface[] $columns
     * @param float $maxWidth
     * @param float $maxHeight
     * @param bool $fullWidth
     */
    public function __construct(
        private readonly TCPDF $pdf,
        private readonly array $columns = [],
        private readonly float $maxWidth = 0,
        private readonly float $maxHeight = 0,
        private readonly bool $fullWidth = false,
    ) {}

    /**
     * @inheritDoc
     */
    public function renderFromArray(array $data): void
    {
        // TODO: Implement renderFromArray() method.
    }

    /**
     * @inheritDoc
     */
    public function renderFromStream(mixed $data): void
    {
        // TODO: Implement renderFromStream() method.
    }

    /**
     * @inheritDoc
     */
    public function renderFromIterable(iterable $data): void
    {
        // TODO: Implement renderFromIterable() method.
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @inheritDoc
     */
    public function getMaxWidth(): float
    {
        return $this->maxWidth;
    }

    /**
     * @inheritDoc
     */
    public function getMaxHeight(): float
    {
        return $this->maxHeight;
    }

    /**
     * @inheritDoc
     */
    public function isFullWidth(): bool
    {
        return $this->fullWidth;
    }

    public function withColumns(array $columns): self
    {
        return new static(
            pdf: $this->pdf,
            columns: $columns,
            maxWidth: $this->maxWidth,
            maxHeight: $this->maxHeight,
            fullWidth: $this->fullWidth,
        );
    }

    public function withColumn(ColumnDefinitionInterface $column): self
    {
        return new static(
            pdf: $this->pdf,
            columns: array_merge($this->columns, [$column]),
            maxWidth: $this->maxWidth,
            maxHeight: $this->maxHeight,
            fullWidth: $this->fullWidth,
        );
    }

    public function withMaxWidth(float $maxWidth): self
    {
        return new static(
            pdf: $this->pdf,
            columns: $this->columns,
            maxWidth: $maxWidth,
            maxHeight: $this->maxHeight,
            fullWidth: $this->fullWidth,
        );
    }

    public function withMaxHeight(float $maxHeight): self
    {
        return new static(
            pdf: $this->pdf,
            columns: $this->columns,
            maxWidth: $this->maxWidth,
            maxHeight: $maxHeight,
            fullWidth: $this->fullWidth,
        );
    }

    public function withFullWidth(bool $fullWidth): self
    {
        return new static(
            pdf: $this->pdf,
            columns: $this->columns,
            maxWidth: $this->maxWidth,
            maxHeight: $this->maxHeight,
            fullWidth: $fullWidth,
        );
    }

    /**
     * @inheritDoc
     */
    public function getPdf(): TCPDF
    {
        return $this->pdf;
    }

    public function withPdf(TCPDF $pdf): TableInterface
    {
        return new static(
            pdf: $pdf,
            columns: $this->columns,
            maxWidth: $this->maxWidth,
            maxHeight: $this->maxHeight,
            fullWidth: $this->fullWidth,
        );
    }
}