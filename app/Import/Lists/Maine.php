<?php namespace App\Import\Lists;


class Maine extends ExclusionList
{

    public $dbPrefix = 'me1';


    public $uri = "https://mainecare.maine.gov/PrvExclRpt/November%202015/PI0008-PM_Monthly_Exclusion_Report%20(csv).csv";


    public $type = 'csv';


    public $fieldNames = [
        'entity',
        'last_name',
        'first_name',
        'middle_initial',
        'prov_type',
        'case_status',
        'sanction_start_date',
        'aka_list'
    ];

    public $originalFieldNames = [
        'ReportNumber',
        'DateRun',
        'ReportName',
        'AsOfDate',
        'LastName',
        'FirstName',
        'MiddleInitial',
        'AliasLastName1',
        'AliasFirstName1',
        'AliasLastName2',
        'AliasFirstName2',
        'AliasLastName3',
        'AliasFirstName3',
        'AliasLastName4',
        'AliasFirstName4',
        'ProvType',
        'CaseStatus',
        'SanctionStartDate'
    ];


    public $deleteColumns = [
        'ReportNumber',
        'DateRun',
        'ReportName',
        'AsOfDate',
    ];


    public $hashColumns = [
        'entity',
        'last_name',
        'first_name',
        'middle_initial',
        'case_status',
        'sanction_start_date',
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $dateColumns = [
        'sanction_start_date' => 17
    ];

    public function preProcess()
    {
        parent::preProcess();

        $finalData = [];

        foreach ($this->data as $values) {

            $arrayWithKeyNames = array_combine($this->originalFieldNames, $values);

            $filteredArray = $this->removeBadColumns($arrayWithKeyNames);

            unset($arrayWithKeyNames);

            $arrayWithEntities = $this->moveEntitiesToNewColumn($filteredArray);

            unset($filteredArray);

            $akaColumns = array_chunk(array_slice($arrayWithEntities, 4, 8, true), 2);

            $akaList = $this->createAkaList($akaColumns);

            array_splice($arrayWithEntities, 4, 8, json_encode($akaList));

            $arrayWithEntities['AkaList'] = $arrayWithEntities[0];

            unset($arrayWithEntities[0]);

            $finalData[] = $arrayWithEntities;
        }

        $this->data = $finalData;
    }


    /**
     * @param $arrayWithKeyNames
     * @return array
     */
    protected function removeBadColumns($arrayWithKeyNames)
    {
        return array_diff_key($arrayWithKeyNames, array_flip($this->deleteColumns));
    }


    /**
     * @param $filteredArray
     * @return array
     */
    protected function moveEntitiesToNewColumn($filteredArray)
    {
        if (empty($filteredArray['FirstName'])) {
            $filteredArray['Entity'] = $filteredArray['LastName'];
            $filteredArray['LastName'] = '';
        } else {
            $filteredArray['Entity'] = '';
        }

        $newArray = ['Entity' => $filteredArray['Entity']] + $filteredArray;

        return $newArray;
    }


    /**
     * @param $akaColumns
     * @return array
     */
    protected function createAkaList($akaColumns)
    {
        $akaName = [];

        foreach ($akaColumns as $akaGroup) {

            if (
                (empty($akaGroup[0]) OR $akaGroup[0] == "N/A") AND
                (empty($akaGroup[1]) OR $akaGroup[0] == "N/A")
            ) {
                continue;
            }

            if ($akaGroup[0] == "N/A") {
                $akaGroup[0] = '';
            }

            if ($akaGroup[1] == "N/A") {
                $akaGroup[1] = '';
            }

            $akaName[] = [
                'last_name' => $akaGroup[0],
                'first_name' => $akaGroup[1]
            ];
        }

        return $akaName;
    }

}
