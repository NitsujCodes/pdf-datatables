<?php

namespace NitsujCodes\PDFDataTable\Factories;

use NitsujCodes\PDFDataTable\Contracts\TableInterface;
use NitsujCodes\PDFDataTable\Entities\Tables\Table;
use TCPDF;

class TableFactory
{
    public static function simple(TCPDF $pdf): TableInterface
    {
        return new Table(
            $pdf
        );
    }
}