<?php

namespace App\Support\Concerns;

use Illuminate\Support\Facades\DB;

trait BuildsYearExpression
{
    protected function yearExpression(string $column): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'sqlite' => "CAST(strftime('%Y', $column) AS INTEGER)",
            'pgsql' => "CAST(EXTRACT(YEAR FROM $column) AS INTEGER)",
            'sqlsrv' => "YEAR($column)",
            'mysql', 'mariadb' => "YEAR($column)",
            default => "YEAR($column)",
        };
    }
}
