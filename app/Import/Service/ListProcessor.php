<?php namespace App\Import\Service;

use App\Import\Lists\ExclusionList;
use App\Import\Lists\HashUtils as HashUtils;

class ListProcessor
{
	/**
	 * @var	ExclusionList	$exclusionList
	 */
	protected $exclusionList;

	/**
	 * Constructor
	 *
	 * @param	ExclusionList	$exclusionList
	 * @throws	\InvalidArgumentException
	 */
	public function __construct(ExclusionList $exclusionList)
    {
        $this->exclusionList = $exclusionList;

		// If there's no dbPrefixes defined, there's no use in continuing
        if (empty($this->exclusionList->dbPrefix))
            throw new \InvalidArgumentException('No table prefix defined in ' . get_class($exclusionList));

        // If there's no field names defined, there's no use in continuing
		if (empty($this->exclusionList->fieldNames))
			throw new \InvalidArgumentException('No field names defined in ' . get_class($exclusionList));

		// If there is no data stop here to avoid wiping the table and replacing it with an empty set
		if (empty($this->exclusionList->data))
			throw new \InvalidArgumentException('The exclusion list contains no data');
    }

	/**
	 * Insert ALL THE THINGS!
	 */
	public function insertRecords()
    {
        $this->exclusionList->preProcess();
        $this->exclusionList->postProcess();

        $this->createNewTable();
        $this->populateNewTable();
        $this->activateNewTable();

        $this->exclusionList->postHook();
    }

	/**
	 * CREATE a new table temporarily to store the new inserts
	 */
	private function createNewTable()
    {
        app('db')->statement('DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_new`');
        app('db')->statement('CREATE TABLE  `' . $this->exclusionList->dbPrefix . '_records_new` LIKE `' . $this->exclusionList->dbPrefix . '_records`');
    }

	/**
	 * INSERT data into the temporary table
	 */
	private function populateNewTable()
    {
        $records = $this->exclusionList->data;

        app('db')->transaction(function () use ($records) {
            foreach ($records as &$record) {
                $hash = $this->getHash($record);
                $record['hash'] = hex2bin($hash);
                app('db')->table($this->exclusionList->dbPrefix . '_records_new')
                    ->insert($record);
            }
        });

        app('db')->commit();
    }

	/**
	 * DROP the currently active table
	 * RENAME the temporary table to replace the dropped one
	 */
	private function activateNewTable()
    {
        // drop old table (this table shouldn't be there, but if it is, clear the way)
        app('db')->statement('DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_old`');

        // rename tables
        app('db')->statement('RENAME TABLE `' . $this->exclusionList->dbPrefix . '_records` TO `' .$this->exclusionList->dbPrefix . '_records_old`, `' . $this->exclusionList->dbPrefix . '_records_new` TO `' . $this->exclusionList->dbPrefix . '_records`');

        // drop old table
        app('db')->statement('DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_old`');
    }

    /**
     * Get a hash of a record
     *
     * @param	array	$record
     * @return	string
     */
    protected function getHash(array $record)
    {
        return HashUtils::generateHash($record, $this->exclusionList);
    }
}
