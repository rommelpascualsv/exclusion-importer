<?php

namespace App\Import\CredentialLists;

use App\Repositories\NppesRepository;

class MedicareOptoutFileReader
{
    private $filerepository;

    private $stats = array (
        'succeeded' => 0,
        'failed' => 0
    );

    const HEADER_POSITIONS = array(
        'NPI_NUMBER' => 0,
        'START_DATE' => 3,
        'END_DATE' => 4,
        'OPT_OUT_FLAG' => 5
    );

    const NPI_NUMBER = 0;
    const START_DATE = 3;
    const END_DATE = 4;
    const OPT_OUT_FLAG = 5;

    const METADATA_LENGTH = 8;

    public function __construct(NppesRepository $filerepository)
    {
        $this->filerepository = $filerepository;
    }

    /**
     * @param $source string containing the filename from command argument
     * @return  $stats array of statistic of successfil and failed update
     */
    public function getStats ($source)
    {
        // Create a resource handler to open the raw input
        $fo = fopen($source, 'rb');

        $prevTrailing = '';
        while ($fc = fread($fo, 1048576)) {

            $arrayBlock = preg_split('/(?<=[\]])/', $fc, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            foreach ($arrayBlock as $block) {

                $startPos = strpos($block, '[');

                //If a block does not contain an opening bracket,
                //it will be concatenated to the previous trailing string
                if ($startPos === false) {

                    $value = $prevTrailing . $block;

                    $this->processJsonString($value);

                    continue;
                }

                $endPos = strpos($block, ']');

                //If there is no closing bracket,
                //this block will be saved and concatenated to the
                //first element of the next block
                if ($endPos === false) {

                    $prevTrailing = $block;

                    continue;
                }

                //Has both opening and closing bracket pair
                $this->processJsonString($block);

            }

        }

        fclose($fo);

        return $this->stats;
    }

    /**
     * The string will be trimmed removing the trailing
     * line breaks, braces, commas and spaces.
     * This will then be decoded and if valid,
     * the metadata will be removed and processed
     * @param $value string containing the record in opt out list
     *
     */
    private function processJsonString ($value)
    {

        $value = trim(preg_replace('/\r|\n/', '', $value), '{}, ');

        $data = json_decode($value);

        if ($data) {
            $data = $this->removeMetadata($data);
        }

        $record = $this->findExistingRecord((string)$data[self::NPI_NUMBER]);

        if ($record) {
            $this->updateRecord($data, $record);
        }

    }

    /**
     * @param $npi NPI number from opt out list
     * @return array of details of NPPES collection existing record
     */
    private function findExistingRecord ($npi)
    {
        return $this->filerepository->find($npi);
    }

    /**
     * @param $data array of data from opt out list with metadata
     * @return array without the metadata
     */
    private function removeMetadata ($data)
    {
        return array_slice($data, self::METADATA_LENGTH);
    }

    /**
     * @param $data array of details per individual from opt out list
     * @param $record array of details of existing record from NPPES collection
     */
    private function updateRecord($data, $record)
    {
        $record['start_date'] = $data[self::START_DATE];
        $record['end_date'] = $data[self::END_DATE];
        $record['opt_out_flag'] = $data[self::OPT_OUT_FLAG];

        $this->filerepository->update($record);
        ++$this->stats['succeeded'];
    }
}