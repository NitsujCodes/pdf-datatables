<?php

namespace NitsujCodes\PDFDataTable;

use Illuminate\Support\ServiceProvider;

/**
 * Laravel Support
 */
class PDFDataTablesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('pdfdatatables', function ($app) {
            return PDFDataTable::getInstance();
        });
    }

    public function boot() {}
}