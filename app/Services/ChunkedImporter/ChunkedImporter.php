<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 03.09.2018
 * Time: 16:05
 */

namespace App\Services\ChunkedImporter;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ChunkedImporter
{
    public const DEFAULT_CHUNK_SIZE = 4096;
    public const DEFAULT_FORMAT = 'Xlsx';
    /** @var Xlsx */
    private $reader;
    /** @var ChunkReadFilter */
    private $filter;
    /** @var string */
    private $filename;
    /** @var array[] */
    public $worksheets;
    /** @var string */
    private $format;

    /**
     * @param $filename
     * @param array $columns
     * @param int $startRow
     * @param string $format
     * @param int $chunkSize
     * @return ChunkedImporter
     * @throws FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function create($filename, $columns = [], $startRow = 0, $format = self::DEFAULT_FORMAT, $chunkSize = self::DEFAULT_CHUNK_SIZE): self
    {
        gc_enable();
        if (!file_exists($filename)) {
            throw new FileNotFoundException(sprintf('File not found at %s', $filename));
        }
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($format);
        $worksheets = $reader->listWorksheetInfo($filename);
        $filter = new ChunkReadFilter($chunkSize, $startRow, $worksheets, $columns);
        $reader->setReadFilter($filter);
        $reader->setReadDataOnly(true);
        return new self($filename, $worksheets, $reader, $filter, $format);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function resetReader()
    {
        unset($this->reader);
        gc_collect_cycles();
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($this->format);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($this->filter);
        $this->reader = $reader;
    }

    protected function __construct(string $filename, array $worksheets, IReader $reader, IReadFilter $filter, string $format)
    {
        $this->filename = $filename;
        $this->format = $format;
        $this->reader = $reader;
        $this->filter = $filter;
        $this->worksheets = $worksheets;
    }

    /**
     * @param callable $callable
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function each(callable $callable)
    {
        foreach ($this->worksheets as $key => $worksheet) {
            $this->filter->setWorksheet($key);
            $this->reader->setLoadSheetsOnly($worksheet['worksheetName']);
            $this->reader->setReadDataOnly(true);
            $this->processSheet($callable, $worksheet);
            $this->resetReader();
        }
    }

    /**
     * @param int $index
     * @param callable $callable
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function processSheet(callable $callable, $worksheet)
    {
        $this->processInChunks($callable, $worksheet);
    }

    /**
     * @param callable $callable
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function processInChunks(callable $callable, $worksheet)
    {
        do {
            $callable($this->reader->load($this->filename)->getActiveSheet());
        } while ($this->filter->advance());
    }
}
