<?php
header("Content-type: application/json");
$result = array(
	'error' => '',
	'description' => array(
		'en' => 'description for en',
		'zh-Hant' => 'description for hant'
	),
	'result' => array(
		array(
			'id' => 'com.creativecrap.ilyrics.plan.week.dev',
			'price' => '$0.99',
			'title' => array(
				'en' => '7-day',
				'zh-Hant' => 'Subscribe for 7 days',
			),
		),
		array(
			'id' => 'com.creativecrap.ilyrics.plan.month.dev',
			'price' => '$1.99',
			'title' => array(
				'en' => 'Month',
				'zh-Hant' => 'Subscribe for a month',
			),
		),
		array(
			'id' => 'com.creativecrap.ilyrics.plan.quarter.dev',
			'price' => '$4.99',
			'title' => array(
				'en' => 'Quater',
				'zh-Hant' => 'Subscribe for a quater',
			),
		),
		array(
			'id' => 'com.creativecrap.ilyrics.plan.year.dev',
			'price' => '$16.99',
			'title' => array(
				'en' => 'Subscribe for a year',
				'zh-Hant' => 'Subscribe for a year',
			),
		),
	)
);
echo json_encode($result);
