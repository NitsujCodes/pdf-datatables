<?php

namespace NitsujCodes\PDFDataTable\Factories;

use Closure;
use NitsujCodes\PDFDataTable\Contracts\ColumnDefinitionInterface;
use NitsujCodes\PDFDataTable\Contracts\InteractiveColumnInterface;
use NitsujCodes\PDFDataTable\Contracts\InteractiveElementInterface;
use NitsujCodes\PDFDataTable\Entities\Columns\ComputedColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\ComputedInteractiveColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\InteractiveColumn;
use NitsujCodes\PDFDataTable\Entities\Columns\SimpleColumn;

class ColumnFactory
{
    /**
     * Create a basic column
     * @param string $label
     * @param string $field
     * @param bool $hasHtmlContent
     * @return ColumnDefinitionInterface
     */
    public static function field(string $label, string $field, bool $hasHtmlContent = false) : ColumnDefinitionInterface
    {
        return new SimpleColumn($label, $field, [], $hasHtmlContent);
    }

    /**
     * Create a basic column that uses a closure to fetch the value
     * @param string $label
     * @param Closure $value
     * @return ColumnDefinitionInterface
     */
    public static function computed(string $label, Closure $value) : ColumnDefinitionInterface
    {
        return new ComputedColumn($label, $value);
    }

    /**
     * Create a column that contains an interactive input
     * @param string $label
     * @param string $field
     * @param InteractiveElementInterface $element
     * @return InteractiveColumnInterface
     */
    public static function interactive(string $label, string $field, InteractiveElementInterface $element) : InteractiveColumnInterface
    {
        return new InteractiveColumn($label, $field, $element);
    }

    /**
     * Create an interactive input column that uses a closure to fetch the value
     * @param string $label
     * @param Closure $value
     * @param InteractiveElementInterface $element
     * @return InteractiveColumnInterface
     */
    public static function computedInteractive(string $label, Closure $value, InteractiveElementInterface $element) : InteractiveColumnInterface
    {
        return new ComputedInteractiveColumn($label, $value, $element);
    }
}