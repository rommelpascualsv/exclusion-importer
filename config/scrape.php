<?php

return [
		'user_agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
		
		'import' => [
				'connecticut_categories' => base_path('resources/scrape/connecticut-categories.json')
		],
		
		'connecticut_categories' => [
				'ambulatory_surgical_centers_recovery_care_centers',
		]
		/* 'connecticut_categories' => [
				'accountancy',
				'healthcare_practitioners' => [
						'acupuncturist',
						'advanced_practice_registered_nurse'
				]
		] */
];