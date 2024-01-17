<?php
	include("../configs/db.php");
	include("../modules/mysqli.php");
	
	define('PAGE_NAME', 'activation');
	
	$pathPrefix = "../";
	
	$dbConn = new mysqli(IWANSHOKOTO_DBSERVER, IWANSHOKOTO_DBUSERS, IWANSHOKOTO_DBPASSWORD);
	
	if ($dbConn->connect_errno == 0 && $dbConn->select_db(IWANSHOKOTO_DBNAME))
	{
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iwanshokoto : Activate Membership</title>
</head>
<link rel="stylesheet" type="text/css" href="../stylesheet/globals.css"/>
<link rel="stylesheet" type="text/css" href="../stylesheet/innerpage.css"/>
<script src="../scripts/jQuery1_7.js" type="text/javascript"></script>
<body>
	<div id="pageContainer" align="center" style="background-color:#FFF;">
    	<?php include("../includes/header.php"); ?>
        <div id="mainContentHolder" style="background-color:#FFF;">
        	<div style="height:23px;"></div>
            
        	<div id="activationemailholder">
            	<div style="text-align:left; margin:20px;"><img src="../images/activate.png" /></div>
                
                <div>
                	<div style="width:auto; float:left;">Email</div>
                    <div style="width:; height:; float:left;"><input type="text" /></div>
                    <div style="clear:both"></div>
                </div>
                
            </div>
        </div>
        <?php include("../includes/footer.php");?>
    </div>
</body>
</html>