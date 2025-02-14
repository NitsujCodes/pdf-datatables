<?php

namespace NitsujCodes\PDFDataTable\Factories;

use Closure;
use NitsujCodes\PDFDataTable\Entities\Columns\ComputedColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\ComputedInteractiveColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\SimpleColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\InteractiveColumn;
use NitsujCodes\PDFDataTable\Interfaces\IColumnDefinition;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveColumn;
use NitsujCodes\PDFDataTable\Interfaces\IInteractiveElement;

class ColumnFactory
{
    /**
     * Create a basic column
     * @param string $label
     * @param string $field
     * @param bool $hasHtmlContent
     * @return IColumnDefinition
     */
    public static function field(string $label, string $field, bool $hasHtmlContent = false) : IColumnDefinition
    {
        return new SimpleColumn($label, $field, $hasHtmlContent);
    }

    /**
     * Create a basic column that uses a closure to fetch the value
     * @param string $label
     * @param Closure $value
     * @return IColumnDefinition
     */
    public static function computed(string $label, Closure $value) : IColumnDefinition
    {
        return new ComputedColumn($label, $value);
    }

    /**
     * Create a column that contains an interactive input
     * @param string $label
     * @param string $field
     * @param IInteractiveElement $element
     * @return IInteractiveColumn
     */
    public static function interactive(string $label, string $field, IInteractiveElement $element) : IInteractiveColumn
    {
        return new InteractiveColumn($label, $field, $element);
    }

    /**
     * Create an interactive input column that uses a closure to fetch the value
     * @param string $label
     * @param Closure $value
     * @param IInteractiveElement $element
     * @return IInteractiveColumn
     */
    public static function computedInteractive(string $label, Closure $value, IInteractiveElement $element) : IInteractiveColumn
    {
        return new ComputedInteractiveColumn($label, $value, $element);
    }
}