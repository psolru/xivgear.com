<?php


namespace App\Services\Importer\Lodestone;


use Exception;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;

class CSVImportHelper
{
    /** @var string */
    protected $resource_url;
    /** @var string */
    protected $scheme_url;
    /** @var string */
    protected $projectDir;
    /** @var int */
    protected $expireTime = 60 * 60 * 24 * 14; // reload data every 2 weeks per default
    /** @var Statement */
    protected $csvHeaderStatement;
    /** @var Statement */
    protected $csvContentStatement;
    /** @var array */
    protected $importFields = [];

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;

        $this->csvHeaderStatement = (new Statement())
            ->offset(0)
            ->limit(1);

        $this->csvContentStatement = (new Statement())
            ->offset(1);
    }

    protected function getDataFileName()
    {
        return $this->projectDir.'/src/Services/Importer/Resources/Lodestone/'.basename($this->resource_url);
    }

    protected function getSchemeFileName()
    {
        return $this->projectDir.'/src/Services/Importer/Resources/Lodestone/'.basename($this->resource_url, '.csv').'.json';
    }

    protected function localDataIsExpired()
    {
        $expireTimestamp = strtotime('now') - $this->expireTime;

        return filemtime($this->getDataFileName()) <= $expireTimestamp;
    }

    private function download()
    {
        if (!file_put_contents( $this->getDataFileName(), file_get_contents($this->resource_url) ))
            throw new Exception('Error downloading '.get_class($this).' Data to '.$this->getDataFileName());
    }

    /**
     * Download data, if not existing, expired, or forced due $force
     * @param bool $force
     * @throws Exception
     */
    public function downloadData(bool $force=false)
    {
        if (
            (
                !file_exists($this->getDataFileName()) ||
                $this->localDataIsExpired()
            ) || $force
        ) {
            $this->download();
        }
    }

    protected function getHeader(Reader $csv): array
    {
        /** @var ResultSet $records */
        $records = $this->csvHeaderStatement->process($csv);

        /**
         * Return only one line for header
         */
        foreach($records as $key => $record)
        {
            return $record;
        }
        return [];
    }

    protected function filterHeader($header): array
    {
        /**
         * Get everything, if not specified
         */
        if (!$this->importFields)
            return [];

        return array_filter($header, function($item) {
            return in_array($item, $this->importFields);
        });
    }

    protected function getContent(Reader $csv): array
    {
        /** @var ResultSet $records */
        $records = $this->csvContentStatement->process($csv);

        $list = [];
        foreach($records as $key => $record)
        {
            $list[] = $record;
        }
        return $list;
    }

    protected function filterContentByHeader($content, $header): array
    {
        /**
        * Filter Content by Header
        */
        foreach($content as $key => $row)
        {
            $filtered = [];
            foreach($header as $headerIndex => $headerName)
            {
                foreach ($row as $rowIndex => $rowValue)
                {
                    if ($headerIndex == $rowIndex) {
                        $filtered[$headerName] = $rowValue;
                    }
                }
            }
            $content[$key] = $filtered;
        }
        return $content;
    }
}