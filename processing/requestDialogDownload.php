<?php
	//script called by DialogManager to download ui for dialogs.
	include("../configs/db.php");
	include("../modules/mysqli.php");
	
	$dialogname = $_POST['dlgname'];
	$dialogSavePath = NULL; //save path of dialog files from configs/app.ini
	
	$ini = parse_ini_file("../configs/app.ini", TRUE);
	if ($ini && array_key_exists('PATHS', $ini))
	{
		if (array_key_exists('DialogSavePath', $ini['PATHS']))
		{
			$fileContent = @file_get_contents($ini['PATHS']['DialogSavePath'] . $dialogname . '.html');
			if ($fileContent)
			{
				//echo JSON object {definition:'', 'dlgname': ''}
				$jsonArray = array('def'=>$fileContent, 'dialogname'=> $dialogname);
				$json = json_encode($jsonArray);
				echo $json;
				flush();
				exit();
			}
		}
	}
	
	echo "0";
	flush();
	exit();
?>