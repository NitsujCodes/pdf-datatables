<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Table extends BaseDTO implements IDTO
{

    public function __construct(
        public readonly TableConfig $config,
        public readonly int $rowCount,
        public readonly Row $headerRow,
        /** @var Row[] */
        public readonly array $dataRows,
        public readonly RowConfig $defaultRowConfig,
        public readonly ColumnConfig $defaultColumnConfig,
    ) {
        parent::__construct();
    }
}