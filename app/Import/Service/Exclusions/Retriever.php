<?php namespace App\Import\Service\Exclusions;


use App\Import\Lists\ExclusionList;

abstract class Retriever
{

    /**
     * Retrieve the data and fill the passed object's data property
     * @param ExclusionList $list
     * @return
     */
    abstract public function retrieveData(ExclusionList $list);


    public function convertDatesToMysql($data, $dateColumns)
    {
        return array_map(function($row) use ($dateColumns) {

            foreach ($dateColumns as $index)
            {
                if (strtotime($row[$index]))
                {
                    $date = new \DateTime($row[$index]);
                    $row[$index] = $date->format('Y-m-d');
                }
                else
                {
                    $row[$index] = null;
                }
            }

            return $row;

        }, $data);
    }

}
