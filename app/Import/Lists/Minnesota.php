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

    private function parseEntity(array $data)
    {
        $array = ['', '', ''];
        $parseData = [];

        foreach ($data as $key => $value) {
            $parseData[] = $this->arrayInsert(2, $value, $array);
        }

        return $parseData;
    }

    private function parseIndividual(array $data)
    {
        $array = [''];
        $parseData = [];

        foreach ($data as $key => $value) {
            $parseData[] = $this->arrayInsert(1, $value, $array);
        }

        return $parseData;
    }

    private function arrayInsert($pos, array $baseArray, array $arrayToInsert)
    {
        $head = array_slice($baseArray, 0, $pos);
        $tail = array_slice($baseArray, $pos);

        return array_merge($head, $arrayToInsert, $tail);
    }

    protected function parse()
    {
        $data = [];

        if (count($this->data) > 8) {
            $data = $this->parseIndividual($this->data);
        } else {
            $data = $this->parseEntity($this->data);
        }

        $this->data = $data;
    }
}
