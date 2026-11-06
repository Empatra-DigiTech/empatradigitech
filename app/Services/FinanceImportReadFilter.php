<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Hanya memuat sel di dalam batas baris dan kolom yang masuk akal untuk buku kas,
 * agar file besar atau "kolom hantu" jauh di kanan tidak menghabiskan memori server.
 */
class FinanceImportReadFilter implements IReadFilter
{
    public function __construct(private int $maxRow, private int $maxColumn)
    {
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        return $row <= $this->maxRow
            && Coordinate::columnIndexFromString($columnAddress) <= $this->maxColumn;
    }
}
