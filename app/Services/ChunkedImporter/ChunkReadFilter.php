<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 03.09.2018
 * Time: 16:13
 */

namespace App\Services\ChunkedImporter;


use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $chunkStart = 0;
    private $workSheetChunkStart = 0;
    private $chunkSize = 0;
    private $worksheet = 0;
    private $worksheets = [];
    private $columns = [];

    public function __construct(int $chunkSize, int $chunkStart = 0, array $worksheets = [], array $columns = [])
    {
        $this->chunkSize = $chunkSize;
        $this->workSheetChunkStart = $chunkStart;
        $this->chunkStart = $chunkStart;
        $this->worksheets = $worksheets;
        $this->columns = $columns;
        $this->setWorksheet($this->worksheet);
    }

    public function setWorksheet($worksheet): self
    {
        $this->worksheet = $worksheet;
        $this->chunkStart = $this->workSheetChunkStart;
        return $this;
    }

    public function getWorksheetInfo()
    {
        return $this->worksheets[$this->worksheet];
    }

    public function next(): bool
    {
        if ($this->hasNextChunk()) {
            $this->chunkStart += $this->chunkSize;
            return true;
        }
        return false;
    }

    public function hasNextChunk(): bool
    {
        $info = $this->getWorksheetInfo();
        return ($this->chunkEnd() <= (int)$info['totalRows']);
    }

    public function advance(): bool
    {
        if ($this->hasNextChunk()) {
            $this->next();
            return true;
        }
        return false;
    }


    /**
     * Should this cell be read?
     *
     * @param string $column Column address (as a string value like "A", or "IV")
     * @param int $row Row number
     * @param string $worksheetName Optional worksheet name
     *
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = ''): bool
    {
        return $this->matchesWorksheet($worksheetName) && $this->matchesColumn($column) && $this->inChunkRange($row);
    }

    private function matchesColumn($column): bool
    {
        return \in_array($column, $this->columns, false);
    }

    private function chunkEnd(): int
    {
        return $this->chunkStart + $this->chunkSize;
    }

    private function matchesWorksheet(string $sheetname): bool
    {
        return $this->worksheets[$this->worksheet]['worksheetName'] === $sheetname;
    }

    private function inChunkRange($row): bool
    {
        return (($row >= $this->chunkStart) && ($row < $this->chunkEnd()));
    }
}
