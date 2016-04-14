<?php

namespace App\Import\Scrape\Data;

class ConnecticutCategories
{
	const CAT_ACCOUNTANCY = 'accountancy';
	const OPT_ACCOUNTANT_CERTIFICATE = 'accountancy|certificate';
	const OPT_ACCOUNTANT_FIRM_PERMIT = 'accountancy|firm_permit';
	const OPT_ACCOUNTANT_LICENSE = 'accountancy|license';
	const CAT_AGRICULTURE = 'agriculture';
	const OPT_ANIMAL_IMPORTERS = 'agriculture|animal_importers';
	const CAT_AMBULATORY_SURGICAL_RECOVERY_CARE_CENTERS = 'ambulatory_surgical_recovery_care_centers';
	const OPT_AMBULATORY_SURGICAL_CENTER = 'ambulatory_surgical_recovery_care_centers|ambulatory_surgical_center';
	
	/**
	 * @var array
	 */
	protected static $categories = [
			'accountancy' => [
					'options' => [
							'certificate' => [
									'label' => 'Certified Public Accountant Certificate',
									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster0',
									'file_name' => 'Certified_Public_Accountant_Certificate'
							],
							'firm_permit' => [
									'label' => 'Certified Public Accountant Firm Permit',
									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster1',
									'file_name' => 'Certified_Public_Accountant_Firm_Permit'
							],
							'license' => [
									'label' => 'Certified Public Accountant Firm Permit',
									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster2',
									'file_name' => 'Certified_Public_Accountant_Firm_Permit'
							]
					]	
			],
			'agriculture' => [
					'label' => 'Agriculture',
					'options' => [
							'animal_importers' => [
									'label' => 'Animal Importers',
									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster3',
									'file_name' => 'Animal_Importers'
							]
					]
			],
			'ambulatory_surgical_recovery_care_centers' => [
					'label' => 'Ambulatory Surgical Centers/Recovery Care Centers',
					'options' => [
							'ambulatory_surgical_center' => [
									'label' => 'Ambulatory Surgical Center',
									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster25',
									'file_name' => 'Ambulatory_Surgical_Center'
							]
					]
			]
	];
	
	/**
	 * Get option
	 * @param string $key
	 * @return array
	 */
	public static function getOption($key)
	{
		list($categoryKey, $optionKey) = explode('|', $key);
		
		return static::$categories[$categoryKey]['options'][$optionKey];
	}
	
	/**
	 * Get options
	 * @param array $keys
	 * @return array
	 */
	public static function getOptions(array $keys)
	{
		$options = [];
		
		foreach ($keys as $key) {
			if (static::isCategoryKey($key)) {
				$options = array_merge(
						$options,
						static::getCategoryOptions($key)
				);
			} else {
				$options[] = static::getOption($key);
			}
		}
		
		return $options;
	}
	
	/**
	 * Get category options
	 * @param string $key
	 * @return array
	 */
	public static function getCategoryOptions($key)
	{
		return array_values(static::$categories[$key]['options']);
	}
	
	/**
	 * Is category key
	 * @param string $key
	 * @return boolean
	 */
	protected static function isCategoryKey($key)
	{
		return (strpos($key, '|') === false);
	}
}