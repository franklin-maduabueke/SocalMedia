<?php
	//function to encode and decode key using base64
	function encodeKey($str)
	{
		return base64_encode($str);
	}
	
	function decodeKey($b64key)
	{
		return base64_decode($b64key);
	}
?>