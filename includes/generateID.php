<?php
	//@param $alnum: should alphabets and numbers be used in the generation. default is true
	//@param $ucase: should uppercase values be used. default is FALSE
	//@param $prefix: optional prefix.
	//@param $suffix: optional suffix.
	function  generateID($markerLength, $alnum = TRUE, $ucase = FALSE, $prefix = '', $suffix = '')
	{
		$prefix = (isset($prefix) && is_string($prefix)) ? $prefix : '';
		$suffix = (isset($suffix) && is_string($suffix)) ? $suffix : '';
		
		//script to create new user for the cms.
		//generate photo name for this.
		//number of letters each photo will carry on its name.
		if ($markerLength <= 0)
			return FALSE;
		
		$alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		
		if (!$alnum)
			$alphabets = substr($alphabets, strlen($alphabets) - 10);
		
		if ($ucase)
			$alphabets = strtoupper($alphabets);
			
		$rangeLength = strlen($alphabets);
		$marker = '';
		
		$marker = '';
		for ($count = 0; $count < $markerLength; $count++)
		{
			$rand = rand(0, $rangeLength - 1);
			$marker .= substr($alphabets, $rand, 1);
		}
		
		return substr($prefix . $marker . $suffix, 0, $markerLength);
	}
?>