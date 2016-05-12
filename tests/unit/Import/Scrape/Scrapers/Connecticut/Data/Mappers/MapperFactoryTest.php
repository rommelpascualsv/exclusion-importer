<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCentersRecoveryCareCenters\AmbulatorySurgicalCenterMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Codeception\TestCase\Test;
use UnitTester;

class MapperFactoryTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        
    }

    protected function _after()
    {
        
    }

    // tests
    public function testCreateByKeys()
    {
        $mapper = MapperFactory::createByKeys(
                        'ambulatory_surgical_centers_recovery_care_centers', 'ambulatory_surgical_center'
        );

        $this->assertInstanceOf(AmbulatorySurgicalCenterMapper::class, $mapper);
    }

}
