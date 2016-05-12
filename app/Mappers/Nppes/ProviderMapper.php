<?php namespace App\Mappers\Nppes;

use App\Mappers\Mapper;
use App\Services\TaxonomyLookup;

class ProviderMapper extends Mapper 
{
	public function __construct(TaxonomyLookup $taxonomyLookup)
	{
		$this->taxonomyLookup = $taxonomyLookup;
	}

	public function map($data)
	{
		$record = [];
		$this->addProperties($record, $this->nppesHeaders, $data);

		$record['parent_organization'] = [];
		$this->addProperties($record['parent_organization'], $this->parentOrganizationHeaders, $data);

		$record['address'] = ['practice' => [], 'mailing' => []];
		$this->addProperties($record['address']['practice'], $this->practiceAddressHeaders, $data);
		$this->addProperties($record['address']['mailing'], $this->mailingAddressHeaders, $data);

		$record['provider'] = ['main' => [], 'other' => []];
		$this->addProperties($record['provider']['main'], $this->mainProviderHeaders, $data);
		$this->addProperties($record['provider']['other'], $this->otherProviderHeaders, $data);

		$record['authorized_official'] = [];
		$this->addProperties($record['authorized_official'], $this->authorizedOfficalHeaders, $data);

		$record['provider_taxonomies'] = [];
		for ($i = 0; $i < 15; ++$i) {
			$record['provider_taxonomies'][$i] = [];
			$headers = $this->appendIndexToHeaders($this->providerTaxonomyHeaders, $i);
			$this->addProperties($record['provider_taxonomies'][$i], $headers, $data);

			// Get the taxonomy value from the code
			$code = $record['provider_taxonomies'][$i]['code'];
			$record['provider_taxonomies'][$i]['value'] = $this->taxonomyLookup->getTypeFromCode($code);
		}
		$record['provider_taxonomies'] = $this->removeEmptyEntries($record['provider_taxonomies']);

		$record['other_provider_identifiers'] = [];
		for ($i = 0; $i < 50; ++$i) {
			$record['other_provider_identifiers'][$i] = [];
			$headers = $this->appendIndexToHeaders($this->otherProviderIdentifierHeaders, $i);
			$this->addProperties($record['other_provider_identifiers'][$i], $headers, $data);
		}
		$record['other_provider_identifiers'] = $this->removeEmptyEntries($record['other_provider_identifiers']);

		return $record;
	}

	private function appendIndexToHeaders($headers, $index)
	{
		return array_map(function ($header) use ($index) {
			return $this->appendIndexToHeader($header, $index);
		}, $headers);
	}

	private function appendIndexToHeader($header, $index)
	{
		return $header . ($index + 1);
	}

	private $nppesHeaders = [
		"npi" => "NPI",
		"entity_type_code" => "Entity Type Code",
		"provider_enumeration_date" => "Provider Enumeration Date",
		"last_update_date" => "Last Update Date",
		"replacement_npi" => "Replacement NPI",
		"deactivation_date" => "NPI Deactivation Date",
		"deactivation_date_reason" => "NPI Deactivation Reason Code",
		"reactivation_date" => "NPI Reactivation Date",
		"ein" => "Employer Identification Number (EIN)",
		"sole_proprietor" => "Is Sole Proprietor",
		"is_organization_subpart" => "Is Organization Subpart"
	];

	private $parentOrganizationHeaders = [
		"lbn" => "Parent Organization LBN",
		"tin" => "Parent Organization TIN"
	];

	private $practiceAddressHeaders = [
		"city_name" => "Provider Business Mailing Address City Name",
		"state_name" => "Provider Business Mailing Address State Name",
		"postal_code" => "Provider Business Mailing Address Postal Code",
		"country_code" => "Provider Business Mailing Address Country Code (If outside U.S.)",
		"phone_number" => "Provider Business Mailing Address Telephone Number",
		"fax_number" => "Provider Business Mailing Address Fax Number",
		"first_line" => "Provider First Line Business Practice Location Address",
		"second_line" => "Provider Second Line Business Practice Location Address"
	];

	private $mailingAddressHeaders = [
		"city_name" => "Provider Business Practice Location Address City Name",
		"state_name" => "Provider Business Practice Location Address State Name",
		"postal_code" => "Provider Business Practice Location Address Postal Code",
		"country_code" => "Provider Business Practice Location Address Country Code (If outside U.S.)",
		"phone_number" => "Provider Business Practice Location Address Telephone Number",
		"fax_number" => "Provider Business Practice Location Address Fax Number",
		"first_line" => "Provider First Line Business Mailing Address",
		"second_line" => "Provider Second Line Business Mailing Address"
	];

	private $mainProviderHeaders = [
		"organization_name" => "Provider Organization Name (Legal Business Name)",
		"first_name" => "Provider First Name",
		"middle_name" => "Provider Middle Name",
		"last_name" => "Provider Last Name (Legal Name)",
		"gender" => "Provider Gender Code",
		"name_prefix" => "Provider Name Prefix Text",
		"name_suffix" => "Provider Name Suffix Text",
		"credential" => "Provider Credential Text"
	];

	private $otherProviderHeaders = [
		"organization_name" => "Provider Other Organization Name",
		"organization_name_type_code" => "Provider Other Organization Name Type Code",
		"first_name" => "Provider Other First Name",
		"middle_name" => "Provider Other Middle Name",
		"last_name" => "Provider Other Last Name",
		"name_prefix" => "Provider Other Name Prefix Text",
		"name_suffix" => "Provider Other Name Suffix Text",
		"credential" => "Provider Other Credential Text",
		"last_name_type_code" => "Provider Other Last Name Type Code"
	];

	private $authorizedOfficalHeaders = [
		"last_name" => "Authorized Official First Name",
		"first_name" => "Authorized Official Middle Name",
		"middle_name" => "Authorized Official Last Name",
		"title" => "Authorized Official Title or Position",
		"phone_number" => "Authorized Official Telephone Number",
		"prefix" => "Authorized Official Name Prefix Text",
		"suffix" => "Authorized Official Name Suffix Text",
		"credential" => "Authorized Official Credential Text"
	];

	private $providerTaxonomyHeaders = [
		"primary" => "Healthcare Provider Primary Taxonomy Switch_",
		"code" => "Healthcare Provider Taxonomy Code_",
		"license_number" => "Provider License Number_",
		"state_code" => "Provider License Number State Code_"
	];

	private $otherProviderIdentifierHeaders = [
		"issuer" => "Other Provider Identifier_",
		"code" => "Other Provider Identifier Type Code_",
		"state" => "Other Provider Identifier State_",
		"other_issuer" => "Other Provider Identifier Issuer_"
	];
}
