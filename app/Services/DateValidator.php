<?php namespace App\Services;

class DateValidator 
{
	public static function validateString($dateString)
	{
    	$date = \DateTime::createFromFormat('m/d/Y', $dateString);
    	return ! (\DateTime::getLastErrors()['warning_count'] || \DateTime::getLastErrors()['error_count']);
	}
}
