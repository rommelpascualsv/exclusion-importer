<?php namespace Test\Unit;

use App\Import\Lists\California;

class CaliforniaTest extends \Codeception\TestCase\Test
{
	private $importer;
	
	/**
	 * Instantiate California Importer
	 */
	protected function _before()
	{
		$this->importer = new California();
	}
	
	public function testValidNpiInProviderColumn()
	{
		$this->importer->data = [
			['Chambi', 'Ruth', '', 'aka: Ruth Chambi-Hernandez', '333 Wilkerson Ave., Stes. B & C, Perris, CA and 4262 Riverfield Ct., Riverside, CA', 'Clinic', '', '1669675443, 00A874530', '2009/03/11', 'indefinitely effective']
		];
		
		$this->importer->preProcess();
		
		$expected = [
			[
				'last_name' 			=> 'Chambi',
				'first_name' 			=> 'Ruth',
				'middle_name' 			=> '',
				'aka_dba' 				=> 'aka: Ruth Chambi-Hernandez',
				'addresses' 			=> '333 Wilkerson Ave., Stes. B & C, Perris, CA and 4262 Riverfield Ct., Riverside, CA',
				'provider_type' 		=> 'Clinic',
				'license_numbers' 		=> '',
				'provider_numbers' 		=> '00A874530',
				'date_of_suspension' 	=> '2009-03-11',
				'active_period' 		=> 'indefinitely effective',
				'npi' 					=> '1669675443',
			    'business'              => ''
			]
		];
		
		$this->assertEquals($expected, $this->importer->data);
	}
	
	public function testValidNoNpi()
	{
		$this->importer->data = [
				['Abbassi', 'Alex', '', '', '18370 Burbank Blvd., Ste. 309, Tarzana, CA, 91356-2804', 'Physician', 'C37895', '00C378950', '08/31/2015', 'Indefinitely effective']
		];
	
		$this->importer->preProcess();
	
		$expected = [
				[
						'last_name' 			=> 'Abbassi',
						'first_name' 			=> 'Alex',
						'middle_name' 			=> '',
						'aka_dba' 				=> '',
						'addresses' 			=> '18370 Burbank Blvd., Ste. 309, Tarzana, CA, 91356-2804',
						'provider_type' 		=> 'Physician',
						'license_numbers' 		=> 'C37895',
						'provider_numbers' 		=> '00C378950',
						'date_of_suspension' 	=> '2015-08-31',
						'active_period' 		=> 'Indefinitely effective',
						'npi' 					=> '',
				        'business'              => ''
				]
		];
	
		$this->assertEquals($expected, $this->importer->data);
	}
	
	public function testLastNameShouldBeSetAsBusinessIfFirstNameIsEmpty()
	{
	     
	    $this->importer->data = [
	        ['A&M Medical', '', '', 'Owner: Jefferson, Michael Hall', '3901 Ursula Ave., Unit #1, Los Angeles, CA', 'Durable Medical Equipment', '', 'MTN01203F, 162911206', '07/04/2012', 'indefinitely effective']
	    ];
	    
	    $this->importer->preProcess();
	    
	    $expected = [
	        [
	            'last_name' 			=> '',
	            'first_name' 			=> '',
	            'middle_name' 			=> '',
	            'aka_dba' 				=> 'Owner: Jefferson, Michael Hall',
	            'addresses' 			=> '3901 Ursula Ave., Unit #1, Los Angeles, CA',
	            'provider_type' 		=> 'Durable Medical Equipment',
	            'license_numbers' 		=> '',
	            'provider_numbers' 		=> 'MTN01203F, 162911206',
	            'date_of_suspension' 	=> '2012-07-04',
	            'active_period' 		=> 'indefinitely effective',
	            'npi' 					=> '',
	            'business'              => 'A&M Medical'
	        ]
	    ];
	    
	    $this->assertEquals($expected, $this->importer->data);	    
	}
}
