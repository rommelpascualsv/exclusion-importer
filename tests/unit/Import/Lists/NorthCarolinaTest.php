<?php namespace Test\Unit;

use App\Import\Lists\NorthCarolina;

class NorthCarolinaTest extends \Codeception\TestCase\Test
{
	private $importer;
	
	/**
	 * Instantiate North Carolina Importer
	 */
	protected function _before()
	{
		$this->importer = new NorthCarolina();
	}
	
	public function testEqualNumberOfColumnsWithHeaderRowRemoved()
	{
		$this->importer->data = [
			['Provider  ID / NPI', 'Provider Name/  Last', 'Provider First Name', 'Address', 'City', 'State', 'Zip Code', 'Health Plan', 'Provider Service Type', 'Exclusion Effective Date', 'Reason for Exclusion'],
			['1588836829', 'BOSS', 'ALIYA SABRIYA', '1913 J N PEASE PL', 'CHARLOTTE', 'NORTH CAROLINA', '28262', 'MEDICAID', 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST', '2015-08-18', 'STATE']
		];
		
		$this->importer->preProcess();
		
		$expected = [
			[
				'first_name' => 'ALIYA SABRIYA',
				'npi' => '1588836829',
				'last_name' => 'BOSS',
				'address_1' => '1913 J N PEASE PL',
				'city' => 'CHARLOTTE',
				'state' => 'NORTH CAROLINA',
				'zip' => '28262',
				'health_plan' => 'MEDICAID',
				'provider_type' => 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST',
				'date_excluded' => '2015-08-18',
				'exclusion_reason' => 'STATE'
			]
		];
		
		$this->assertEquals($expected, $this->importer->data);
	}
	
	public function testAdditionalColumnShouldNotBeIncluded()
	{
		$this->importer->data = [
				['Provider  ID / NPI', 'Provider Name/  Last', 'Provider First Name', 'Address', 'City', 'State', 'Zip Code', 'Health Plan', 'Provider Service Type', 'Exclusion Effective Date', 'Reason for Exclusion'],
				['1588836829', 'BOSS', 'ALIYA SABRIYA', '1913 J N PEASE PL', 'CHARLOTTE', 'NORTH CAROLINA', '28262', 'MEDICAID', 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST', '2015-08-18', 'STATE', 'EXTRA COLUMN']
		];
	
		$this->importer->preProcess();
	
		$expected = [
				[
						'first_name' => 'ALIYA SABRIYA',
						'npi' => '1588836829',
						'last_name' => 'BOSS',
						'address_1' => '1913 J N PEASE PL',
						'city' => 'CHARLOTTE',
						'state' => 'NORTH CAROLINA',
						'zip' => '28262',
						'health_plan' => 'MEDICAID',
						'provider_type' => 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST',
						'date_excluded' => '2015-08-18',
						'exclusion_reason' => 'STATE'
				]
		];
	
		$this->assertEquals($expected, $this->importer->data);
	}
	
	public function testInvalidNPIShouldNotBeIncluded()
	{
		$this->importer->data = [
				['Provider  ID / NPI', 'Provider Name/  Last', 'Provider First Name', 'Address', 'City', 'State', 'Zip Code', 'Health Plan', 'Provider Service Type', 'Exclusion Effective Date', 'Reason for Exclusion'],
				['1588836829ABC', 'BOSS', 'ALIYA SABRIYA', '1913 J N PEASE PL', 'CHARLOTTE', 'NORTH CAROLINA', '28262', 'MEDICAID', 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST', '2015-08-18', 'STATE', 'EXTRA COLUMN']
		];
	
		$this->importer->preProcess();
	
		$expected = [
				[
						'first_name' => 'ALIYA SABRIYA',
						'npi' => '',
						'last_name' => 'BOSS',
						'address_1' => '1913 J N PEASE PL',
						'city' => 'CHARLOTTE',
						'state' => 'NORTH CAROLINA',
						'zip' => '28262',
						'health_plan' => 'MEDICAID',
						'provider_type' => 'MENTAL HEALTH, PROFESSIONAL, CLINICAL, FAMILY AND MARRIGE THERAPIEST',
						'date_excluded' => '2015-08-18',
						'exclusion_reason' => 'STATE'
				]
		];
	
		$this->assertEquals($expected, $this->importer->data);
	}
}