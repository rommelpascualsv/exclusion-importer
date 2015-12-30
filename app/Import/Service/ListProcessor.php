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
        if(method_exists($this->exclusionList, 'preProcess')){
            $this->exclusionList->data = $this->exclusionList->preProcess($this->exclusionList->data);
        }

        $this->exclusionList->data = $this->convertToAssoc($this->exclusionList->data);

        $this->createNewTable();

        $this->populateNewTable();

        $this->activateNewTable();

        if (method_exists($this->exclusionList, 'postHook'))
        {
            $this->exclusionList->postHook();
        }
    }


	/**
	 * CREATE a new table temporarily to store the new inserts
	 */
	private function createNewTable()
    {
        DB::query(Database::INSERT, 'DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_new`')
            ->execute('exclusion_lists_staging');
       DB::query(Database::INSERT, 'CREATE TABLE  `' . $this->exclusionList->dbPrefix . '_records_new` LIKE `' . $this->exclusionList->dbPrefix . '_records`')
            ->execute('exclusion_lists_staging');
    }


	/**
	 * INSERT data into the temporary table
	 */
	private function populateNewTable()
    {
		$headers = $this->exclusionList->fieldNames;
		$records = $this->exclusionList->data;

        array_push($headers, 'hash');

        $insert = DB::insert($this->exclusionList->dbPrefix . '_records_new', $headers);

        foreach ($records as &$record)
        {
			$hash = $this->getHash($record);

            array_push($record, DB::expr("UNHEX('{$hash}')"));

            if (count($record) == count($headers))
            {
                $insert->values($record);
            }
        }

        return $insert->execute('exclusion_lists_staging');
    }


	/**
	 * DROP the currently active table
	 * RENAME the temporary table to replace the dropped one
	 */
	private function activateNewTable()
    {
        // drop old table (this table shouldn't be there, but if it is, clear the way)
        DB::query(Database::INSERT, 'DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_old`')
            ->execute('exclusion_lists_staging');

        // rename tables
        DB::query(Database::INSERT, 'RENAME TABLE `' . $this->exclusionList->dbPrefix . '_records` TO `' .$this->exclusionList->dbPrefix . '_records_old`, `' . $this->exclusionList->dbPrefix . '_records_new` TO `' . $this->exclusionList->dbPrefix . '_records`')
            ->execute('exclusion_lists_staging');

        // drop old table
        DB::query(Database::INSERT, 'DROP TABLE IF EXISTS `' . $this->exclusionList->dbPrefix . '_records_old`')
            ->execute('exclusion_lists_staging');
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


    protected function convertToAssoc($data)
    {
        return array_map(function($item) {

            return array_combine($this->exclusionList->fieldNames, $item);

        }, $data);
    }
}
