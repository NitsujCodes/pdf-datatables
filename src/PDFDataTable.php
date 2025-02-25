<?php

namespace NitsujCodes\PDFDataTable;

use Exception;
use NitsujCodes\PDFDataTable\Contracts\TableBuilderInterface;
use NitsujCodes\PDFDataTable\Contracts\TableInterface;
use TCPDF;

class PDFDataTable
{
    private static ?PDFDataTable $instance = null;

    private TCPDF $pdf;

    private array $tableConfigs = [];
    private array $tableRowConfigs = [];
    private array $tableRowCellConfigs = [];

    private array $tables;
    private int $tableCount;

    private ?string $currentTableName;
    private ?string $currentRowId;
    private ?string $currentCellId;

    /**
     * @throws Exception
     */
    public function __construct(?TCPDF &$pdf = null)
    {
        if (!is_null($pdf)) {
            $this->pdf = &$pdf;
        } else {
            $this->pdf = new TCPDF();
        }

        $this->currentTableName = null;
        $this->currentRowId = null;
        $this->currentCellId = null;

        $this->tableCount = 0;
        $this->tables = [];
    }

    public function attachTPCDF(TCPDF &$pdf): void
    {
        $this->pdf = &$pdf;
    }

    public function getPDF(): TCPDF
    {
        return $this->pdf;
    }

    public function initTCPDF(
        $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskcache = false,
        $pdfa = false
    ): void
    {
        $this->pdf = new TCPDF(
            $orientation,
            $unit,
            $format,
            $unicode,
            $encoding,
            $diskcache,
            $pdfa,
        );
    }

    public static function getInstance(): PDFDataTable
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDFDataTable();
        }

        return self::$instance;
    }
}