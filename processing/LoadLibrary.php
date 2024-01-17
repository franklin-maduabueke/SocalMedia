<?php
	//loads the javascript files
	include("../configs/db.php");
	include("../modules/mysqli.php");
	include("../includes/user_checker.php");
	include("../includes/commons.php");
	include("../includes/encodeDecodeKey.php");
	
	$sessionGood = TRUE;
	if (!userSessionGood(SITEUSERAUTHENTICATIONKEY))
		$sessionGood = FALSE;
		
	$scriptURL = $_POST['url'];
	$scriptsToLoad = $_POST['libs'];
	
	if (isset($scriptURL, $scriptsToLoad) && strlen($scriptsToLoad))
	{
		$scriptsToLoad = explode(',', $scriptsToLoad);
		$scriptDefs = '';
		
		for($i = 0; $i < count($scriptsToLoad); $i++)
		{
			if (!empty($scriptsToLoad[$i]))
			{
				$loadedScript = @file_get_contents($scriptURL . trim($scriptsToLoad[$i]));
				if ($loadedScript)
					$scriptDefs .= $loadedScript;
				else
				{
					$jsonArray = array('error'=>1, 'message'=>'Unable to load script (' . $script . ').', 'defs'=>$scriptDefs);
					$json = json_encode($jsonArray);
					echo $json;
					flush();
					exit();
				}
			}
		}
		
		$jsonArray = array('error'=>0, 'defs'=>$scriptDefs);
		$json = json_encode($jsonArray);
		echo $json;
		flush();
		exit();
	}
	
	$jsonArray = array('error'=>1, 'message'=>'Scripts to load where not specified');
	$json = json_encode($jsonArray);
	echo $json;
	flush();
	exit();
?>