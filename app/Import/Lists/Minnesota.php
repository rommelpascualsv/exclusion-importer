<?php namespace App\Import\Lists;

class Minnesota extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'mn1';

    /**
     * @var string
     * http://www.dhs.state.mn.us/main/idcplg?IdcService=GET_FILE&RevisionSelectionMethod=LatestReleased&Rendition=Primary&allowInterrupt=1&noSaveAs=1&dDocName=dhs16_177447 - Entity Exclusion
     */
    public $uri = 'http://www.dhs.state.mn.us/main/idcplg?IdcService=GET_FILE&RevisionSelectionMethod=LatestReleased&Rendition=Primary&allowInterrupt=1&noSaveAs=1&dDocName=dhs16_177448';

    /**
     * @var string
     */
    public $type = 'xls';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'provider_type_description',
        'sort_name',
        'last_name',
        'first_name',
        'middle_name',
        'effective_date_of_exclusion',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip'
    ];

    /**
     * @var date columns
     * @inherit preProcess
     */
    public $dateColumns = [
       'effective_date_of_exclusion' => 5
    ];

    public $hashFields = [
        'last_name',
        'first_name',
        'middle_name',
        'effective_date_of_exclusion',
        'sort_name'
    ];

    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param array processed data
     * @return array Parsed data for Entity
     */
    private function parseEntity(array $data)
    {
        $array = ['', '', ''];

        return array_map(function ($item) use ($array) {
            return $this->arrayInsert(2, $item, $array);
        }, $data);
    }

    /**
     * @param array processed data
     * @return array Parsed data for Individual
     */
    private function parseIndividual(array $data)
    {
        $array = [''];

        return array_map(function ($item) use ($array) {
            return $this->arrayInsert(1, $item, $array);
        }, $data);
    }

    /**
     * Insert array within array
     * @param array
     * @return array
     */
    private function arrayInsert($pos, array $baseArray, array $arrayToInsert)
    {
        $head = array_slice($baseArray, 0, $pos);
        $tail = array_slice($baseArray, $pos);

        return array_merge($head, $arrayToInsert, $tail);
    }

    /**
     * Parse data if Entity or Individual
     * @return void data method
     */
    protected function parse()
    {
        $data = [];
        foreach ($this->data as $key => $value) {

            if (count($value[0]) === 8) {
                $data = array_merge($data, $this->parseEntity($value));
            }

            if (count($value[0]) === 10) {
                $data = array_merge($data, $this->parseIndividual($value));
            }

        }
        
        $this->data = $data;
    }
}
