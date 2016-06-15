<?php namespace Test\Unit;

use App\Import\Lists\Arizona;

class ArizonaTest extends \Codeception\TestCase\Test
{
	private $importer;
	
	/**
	 * Instantiate Arizona Importer
	 */
	protected function _before()
	{
		$this->importer = new Arizona();
	}
	
	public function testMiddleInitialExistsInFirstName()
	{
		$this->importer->data = [
			['BASSETT', 'PETER A', '10/26/2012', 'SURGERY, ORAL & MAXILLOFACIAL', '1083677249'],
			['ASIN', 'GERALD S.', '09/24/2010', 'INTERNAL MEDICINE', '1770576365']
		];
		
		$this->importer->preProcess();
		
		$expected = [
			[
				'last_name_company_name' => 'BASSETT',
				'middle' => 'A',
				'first_name' => 'PETER',
				'term_date' => '10/26/2012',
				'specialty' => 'SURGERY, ORAL & MAXILLOFACIAL',
				'npi_number' => '1083677249'
			],[
				'last_name_company_name' => 'ASIN',
				'middle' => 'S',
				'first_name' => 'GERALD',
				'term_date' => '09/24/2010',
				'specialty' => 'INTERNAL MEDICINE',
				'npi_number' => '1770576365'
			]
		];
		
		$this->assertEquals($expected, $this->importer->data);
	}
	
	public function testNoMiddleInitialInFirstName()
	{
		$this->importer->data = [
			['BASSETT', 'PETER', '10/26/2012', 'SURGERY, ORAL & MAXILLOFACIAL', '1083677249'],
			['ASIN', 'GERALD ', '09/24/2010', 'INTERNAL MEDICINE', '1770576365']
		];
		
		$this->importer->preProcess();
		
		$expected = [
			[
				'last_name_company_name' => 'BASSETT',
				'middle' => '',
				'first_name' => 'PETER',
				'term_date' => '10/26/2012',
				'specialty' => 'SURGERY, ORAL & MAXILLOFACIAL',
				'npi_number' => '1083677249'
			],[
				'last_name_company_name' => 'ASIN',
				'middle' => '',
				'first_name' => 'GERALD',
				'term_date' => '09/24/2010',
				'specialty' => 'INTERNAL MEDICINE',
				'npi_number' => '1770576365'
			]
		];
		
		$this->assertEquals($expected, $this->importer->data);
	}
}