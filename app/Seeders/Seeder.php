<?php namespace App\Seeders;

use League\Csv\Reader;
use Carbon\Carbon;

abstract class Seeder 
{
	/**
	 * @var \App\Repositories\Repository
	 */
	protected $repository;

	/**
	 * @var \App\Mappers\Mapper
	 */
	protected $mapper;

	/**
	 * @var \App\Seeders\SeederLog
	 */
	protected $logger;

	public function seed($file, $asOfDate = null)
	{
		$this->prepare();

		$reader = Reader::createFromPath($file);
		$headers = $reader->fetchOne(0);
		$successCount = $failCount = 0;
		$lastModified = Carbon::now('UTC')->toDateTimeString();

		foreach ($reader as $index => $row) {
			// Skip the header and the last record from the iterator
			if (count($row) === 1 || $index === 0) { continue; }

			// Invalid row
			if (count($headers) != count($row)) {
				++$failCount;
				$this->logError($row);
				continue;
			}

			$row = array_combine($headers, $row);
			$record = $this->mapper->map($row);
			$record['as_of'] = is_null($asOfDate) ? date('m/01/Y') : $asOfDate;
			$record['last_modified'] = $lastModified;
			$this->persistRecord($record);

			++$successCount;
		}

		return [
			"succeeded" => $successCount,
			"failed" => $failCount
		];
	}

	protected function prepare() { }

	abstract protected function logError($row);

	abstract protected function persistRecord($record);
}
