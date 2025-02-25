<?php

namespace NitsujCodes\PDFDataTable\Entities\Columns;

use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;

class ComputedColumn extends SimpleColumn implements ColumnDefinitionInterface
{
    public function __construct(
        string $label,
        private readonly \Closure $value,
        private readonly bool $hasHtmlContent = false,
    ) {
        parent::__construct($label, '', []);
    }

    public function getValue(array $row): string
    {
        return ($this->value)($row);
    }

    public function hasHtmlContent(): bool
    {
        return $this->hasHtmlContent;
    }
}