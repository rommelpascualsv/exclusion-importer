<?php
namespace Import\Scrape\Scrapers\Connecticut\Data;


use App\Import\Scrape\Scrapers\Connecticut\Data\CsvDir;

class CsvDirTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Test if the csv directory data in CsvDirTest::getDataFromFilesystem is correct
     * Skip test for now.
     */
//     public function testGetDataFromFilesystem()
//     {
//         $csvDirData = CsvDir::getDataFromFilesystem(
//             app('scrape_test_filesystem'),
//             'connecticut/csv'
//         );
        
//         $expectedCsvDirData = [
//             [
//                 'category' => 'ambulatory_surgical_centers_recovery_care_centers',
//                 'option' => 'ambulatory_surgical_center',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
//             ],
//             [
//                 'category' => 'child_day_care_licensing_program',
//                 'option' => 'child_day_care_centers_and_group_day_care_homes_closed_1_year',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/child_day_care_licensing_program/child_day_care_centers_and_group_day_care_homes_closed_1_year.csv')
//             ],
//             [
//                 'category' => 'child_day_care_licensing_program',
//                 'option' => 'family_day_care_homes_total_by_date_active',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/child_day_care_licensing_program/family_day_care_homes_total_by_date_active.csv')
//             ],
//             [
//                 'category' => 'controlled_substances_practitioners_labs_manufacturers',
//                 'option' => 'controlled_substance_laboratories',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/controlled_substance_laboratories.csv')
//             ],
//             [
//                 'category' => 'controlled_substances_practitioners_labs_manufacturers',
//                 'option' => 'manufacturers_of_drugs_cosmetics_and_medical_devices',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/manufacturers_of_drugs_cosmetics_and_medical_devices.csv')
//             ],
//             [
//                 'category' => 'healthcare_practitioners',
//                 'option' => 'acupuncturist',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/healthcare_practitioners/acupuncturist.csv')
//             ],
//             [
//                 'category' => 'infirmaries_clinics',
//                 'option' => 'family_planning_clinics',
//                 'file_path' => codecept_data_dir('scrape/connecticut/csv/infirmaries_clinics/family_planning_clinics.csv')
//             ],
//         ];
        
//         $this->assertSame($csvDirData, $expectedCsvDirData);
//     }
}