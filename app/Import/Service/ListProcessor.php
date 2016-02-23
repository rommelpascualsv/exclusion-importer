<?php namespace App\Import\Service;

use App\Import\Lists\ExclusionList;

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
        $this->exclusionList->convertToAssoc();
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

        foreach ($records as &$record)
        {
			$hash = $this->getHash($record);

            // we can do this with db::raw if need be.
            // i think this is cleaner, but we need to make sure it won't break the hashes.
            $record['hash'] = hex2bin($hash);

//            not sure what to do about this. why were we doing this?
//            if (count($record) != count($headers))
//            {
//                $insert->values($record);
//            }
        }

        return app('db')
            ->table($this->exclusionList->dbPrefix . '_records_new')
            ->insert($records);
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
        if (empty($this->exclusionList->hashColumns))
        {
            $this->exclusionList->hashColumns = $this->exclusionList->fieldNames;
        }

        $hashData = [];

        foreach ($record as $key => $value)
        {
            if (in_array($key, $this->exclusionList->hashColumns))
            {
                $hashData[] = $value;
            }
        }

        $string = preg_replace("/[^A-Za-z0-9]/", '', trim(strtoupper(implode('', $hashData))));

        //adds the exclusion list prefix to the hash to avoid having identical hashes in different lists
        if ($this->exclusionList->shouldHashListName) {

            $listName = trim(strtoupper($this->exclusionList->dbPrefix));

            $string .= $listName;
        }

        return md5($string);
    }


}
