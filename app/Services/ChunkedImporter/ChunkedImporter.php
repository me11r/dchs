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

class ChunkedImporter
{
    public const DEFAULT_CHUNK_SIZE = 1;
    public const DEFAULT_FORMAT = 'Xlsx';
    /** @var Xlsx */
    private $reader;
    /** @var ChunkReadFilter */
    private $filter;
    /** @var string */
    private $filename;
    /** @var array[] */
    private $worksheets;

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
        if (!file_exists($filename)) {
            throw new FileNotFoundException(sprintf('File not found at %s', $filename));
        }
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($format);
        $reader->setReadDataOnly(true);
        $worksheets = $reader->listWorksheetInfo($filename);
        $filter = new ChunkReadFilter($chunkSize, $startRow, $worksheets, $columns);
        $reader->setReadFilter($filter);
        return new self($filename, $worksheets, $reader, $filter);
    }

    protected function __construct(string $filename, array $worksheets, IReader $reader, IReadFilter $filter)
    {
        $this->filename = $filename;
        $this->reader = $reader;
        $this->filter = $filter;
        $this->worksheets = $worksheets;
    }

    /**
     * @param callable $callable
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function eachSheet(callable $callable)
    {
        foreach ($this->worksheets as $key => $worksheet) {
            $this->filter->setWorksheet($key);
            $this->reader->setLoadSheetsOnly($worksheet['title']);
            $this->eachChunk($callable);
        }
    }

    public function eachChunk(callable $callable)
    {
        do {
            $callable($this->reader->load($this->filename));
        }while($this->filter->advance());
    }
}
