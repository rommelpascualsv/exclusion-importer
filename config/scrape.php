<?php

return [
		'user_agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
		
		'import' => [
				'connecticut_categories' => base_path('resources/scrape/connecticut-categories.json')
		],
		
		'connecticut_categories' => [
				'ambulatory_surgical_centers_recovery_care_centers',
				'controlled_substances_practitioners_labs_manufacturers',
				'child_day_care_licensing_program',
				'drug_control_pharmacy_pharmacists_etc',
				'emergency_medical_services',
				'healthcare_practitioners',
				'hemodialysis_centers',
				'hospitals',
				'infirmaries_clinics',
				'long_term_care_assisted_living_facilities_home_health_care_hospice',
				'medical_marijuana_producer_dispensary_facility_dispensary_etc',
				'mental_health_care',
				'registered_sanitarians',
				'substance_abuse_care'
		]
		/* 'connecticut_categories' => [
				'ambulatory_surgical_centers_recovery_care_centers',
		] */
		/* 'connecticut_categories' => [
				'accountancy',
				'healthcare_practitioners' => [
						'acupuncturist',
						'advanced_practice_registered_nurse'
				]
		] */
];