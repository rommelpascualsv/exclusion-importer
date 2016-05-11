<?php namespace App\Import\OFAC\SDN;

class Query
{
    /**
     * Table name for the extending class
     *
     * @var     string      $table
     */
    protected static $table = '';

    public static function get_all()
    {
    }

    /**
     * @param $uid
     * @return null|Query
     */
    public static function get_by_uid($uid)
    {
        $records = app('db')
            ->table(static::$table)
            ->where('uid', $uid);

        if (count($records) == 0) {
            return null;
        }

        return static::_instantiate($records[0]);
    }

    public static function getBySdnEntryId($sdn_entry_id)
    {
        $result_set = [];

        $records = app('db')
            ->table(static::$table)
            ->where('sdn_entry_id', $sdn_entry_id);

        foreach ($records as $record) {
            $result_set[] = static::_instantiate($record);
        }

        return $result_set;
    }

    public static function get_by_id()
    {
    }

    /**
     * Map the fields from a row retrieved from the Database to a new instance of this class
     *
     * @param   array       $row    - A row from a database result
     * @return  static              - An instance of extending object
     */
    protected static function _instantiate(array $row)
    {
        $me = new static();

        foreach ($row as $key => $value) {
            if (property_exists($me, $key)) {
                $me->$key = $value;
            }
        }

        return $me;
    }
}
