<?php
	//class representing a chat
	class JDChat {
		function __construct($dbConn)
		{
			if (isset($dbConn) && is_a($dbConn, "mysqli"))
			{
				$this->mDBConn = $dbConn;
			}
		}
		
		//used to register a chat user
		public function registerChatUser($username, $password, $id)
		{
			//check if user already exists
			if (isset($username, $password, $id))
			{
				$sql = "SELECT UserGenID FROM chatusers WHERE UserGenID='$id' || Username='$username'";
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					//register
					$sql = "INSERT INTO chatusers (Username, Password, UserGenID) VALUES('$username', '$password', '$id')";
					$this->mDBConn->query($sql);
					if ($this->mDBConn->affected_rows > 0)
						return TRUE;
				}
			}
			
			return FALSE;
		}
		
		private $mDBConn = NULL;
	}
?>