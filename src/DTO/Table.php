<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Table extends BaseDTO implements IDTO
{

    public function __construct(
        public readonly string $uniqueName,
        public readonly TableConfig $config,
        public readonly RowConfig $defaultRowConfig,
        public readonly ColumnConfig $defaultColumnConfig,

        // Optionals
        /** @var Row[] */
        public readonly array $dataRows = [],
        public readonly int $rowCount = 0,
        public readonly ?Row $headerRow = null,

    ) {
        parent::__construct();
    }
}