<?php

namespace Test\Unit;

use App\Import\Lists\Sam\OpmExtrasRemover;
use CDM\Test\TestCase;
use Illuminate\Support\Facades\DB;

class OpmExtrasRemoverTest extends TestCase
{

    private $repo;

    public function setUp()
    {
        parent::setUp();

        $this->app->withFacades();

        $this->repo = new OpmExtrasRemover();

    }

    public function tearDown()
    {
        DB::delete('DELETE FROM sam_records_temp');
    }

    public function testShouldRemoveOpmExtras()
    {
        // setup table
        $data = array (['NAME' => '2001 BATH AVENUE PHARMACY' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '2001 BATH AVE FL 1' , 'City' => 'BROOKLYN' , 'State' => 'NY' , 'Zip' => '112144813' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'HHS' , 'Active_Date' => '2011-08-18'],
            ['NAME' => '2001 BATH AVENUE PHARMACY' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '2001 BATH AVE FL 1' , 'City' => 'BROOKLYN' , 'State' => 'NY' , 'Zip' => '112144813' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'OPM' , 'Active_Date' => '2011-09-29'],
            ['NAME' => 'A PERFECT SMILE ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '6500 COW PEN ROAD' , 'City' => 'MIAMI LAKES' , 'State' => 'FL' , 'Zip' => '33014' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'HHS' , 'Active_Date' => '2007-11-20'],
            ['NAME' => 'A PERFECT SMILE ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '6500 COW PEN ROAD' , 'City' => 'MIAMI LAKES' , 'State' => 'FL' , 'Zip' => '33014' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'OPM' , 'Active_Date' => '2008-01-16'],
            ['NAME' => 'A-COMMUNITY HOME HEALTH ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '33 NORTHEAST 4TH ST.' , 'City' => 'MIAMI' , 'State' => 'FL' , 'Zip' => '33101' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'HHS' , 'Active_Date' => '1998-03-19'],
            ['NAME' => 'A-COMMUNITY HOME HEALTH ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '33 NORTHEAST 4TH ST.' , 'City' => 'MIAMI' , 'State' => 'FL' , 'Zip' => '33101' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'OPM' , 'Active_Date' => '1998-07-16'],
            ['NAME' => 'A-COMMUNITY HOME HEALTH ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '33 NORTHEAST 4TH ST.' , 'City' => 'MIAMI' , 'State' => 'FL' , 'Zip' => '33101' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'OPM' , 'Active_Date' => '2003-05-19'],
            ['NAME' => 'BGF TRANSPORTATION ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '2483 WEST 16TH ST. ,  APT 10B' , 'City' => 'BROOKLYN' , 'State' => 'NY' , 'Zip' => '11214' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'HHS' , 'Active_Date' => '2003-08-20'],
            ['NAME' => 'BGF TRANSPORTATION ,  INC.' , 'FIRST' => '' , 'Middle' => '' , 'LAST' => '' , 'Address_1' => '2483 WEST 16TH ST. ,  APT 10B' , 'City' => 'BROOKLYN' , 'State' => 'NY' , 'Zip' => '11214' , 'Exclusion_Program' => 'Reciprocal' , 'Exclusion_Type' => 'Prohibition/Restriction' , 'Excluding_Agency' => 'OPM' , 'Active_Date' => '2003-10-31']
        );

        DB::statement("DROP TABLE IF EXISTS sam_records_temp");
        DB::statement("CREATE TABLE IF NOT EXISTS sam_records_temp LIKE sam_records");
        DB::table('sam_records_temp')->insert($data);

        //$expected = 10;   // fail
        $expected = 4;      // pass

        $this->repo->invoke();

        $rs = DB::select('SELECT count(1) AS rec_count FROM sam_records_temp');

        $actual = $rs[0]->rec_count;

        $this->assertEquals($expected, $actual);
    }
}