<?php
	//class representing a member of iwanshokoto.com
	class Member {
		function __construct($memberid, $dbConn)
		{
			if (isset($memberid, $dbConn) && is_a($dbConn, "mysqli"))
			{
				$this->mDBConn = $dbConn;
				$sql = "SELECT m.Username, m.LastLogin, m.IsOnline, Gender, DOB, Seeking, Location, loc.StateName, LookingFor, Hobby, Occupation, Relationship, Height, FavQuotes, AboutMe, BiggestAsset, MyFairyTaleRomance, PhotoFormat, IntrestedIn, CountryName, SexOrientation FROM member_profile AS mp LEFT JOIN members AS m ON mp.MemGenID=m.MemGenID LEFT JOIN locations AS loc ON mp.Location=loc.LTID LEFT JOIN country ON mp.CountryResidence=country.CTID WHERE mp.MemGenID='$memberid'";
				
				$result = $this->mDBConn->query($sql);
				
				if ($result && $result->num_rows > 0)
				{
					$row = $result->fetch_array();
					$this->mMemberID = $memberid;
					$this->mProfile = sprintf('{"memgenid": "%s", "username": "%s", "dob": "%s", "seeking": "%s", "locationid": "%s", "location": "%s", "lookingfor": "%s", "hobby": "%s", "occupation": "%s", "relationship": "%s", "height": "%s", "favquote": "%s", "aboutme": "%s", "biggestasset": "%s", "fairytaleromance": "%s", "gender": "%s", "intrest": "%s", "countryresidence": "%s", "sexorientation": "%s", "lastlogin": "%s", "isonline": "%s"}', $this->mMemberID, $row['Username'], $row['DOB'], $row['Seeking'], $row['Location'], $row['StateName'], $row['LookingFor'], $row['Hobby'], $row['Occupation'], $row['Relationship'], $row['Height'], $row['FavQuotes'], $row['AboutMe'], $row['BiggestAsset'], $row['MyFairyTaleRomance'], $row['Gender'], $row['IntrestedIn'], $row['CountryName'], $row['SexOrientation'], $row['LastLogin'], $row['IsOnline']);
				}
				else
					throw new Exception("Unable to find member information.");
			}
			else
				throw new Exception("Invalid parameters for constructor");
		}
		
		//gets the members profile information
		public function getProfile()
		{
			return $this->mProfile;
		}
		
		//gets the users album collection
		public function getAlbumCollection()
		{
			$albumCollection = array();
			$sql = sprintf("SELECT AlbumGenID, AlbumTitle, AlbumDescription, DateCreated FROM album WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			
			if ($result && $result->num_rows > 0)
				for (; $row = $result->fetch_array();)
					$albumCollection[$row['AlbumGenID']] = sprintf('{"albumgenid": "%s", "title": "%s", "description": "%s", "datecreated": "%s"}', $row['AlbumGenID'], $row['AlbumTitle'], $row['AlbumDescription'], date("jS F, Y", strtotime($row['DateCreated'])));
			
			return $albumCollection;
		}
		
		//gets the photoid for an album.
		public function getAlbumPhotoID($albumid, $limit = 0)
		{
			$photoCollection = array();
			if ($limit > 0)
				$limitStatement = " LIMIT $limit ";
				
			$sql = sprintf("SELECT PhotoGenID, UploadDate, PhotoFormat FROM album_photo WHERE AlbumGenID='%s' $limitStatement", $albumid);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				for (;$row = $result->fetch_array();)
					$photoCollection[$row['PhotoGenID']] = sprintf('{"photogenid": "%s", "uploaddate": "%s", "photoformat": "%s"}', $row['PhotoGenID'], date("jS F, Y", $row['UploadDate']), $row['PhotoFormat']);
			}
			
			return $photoCollection;
		}
		
		//gets the members pic
		//@param: height: to resize the image to
		//@param: width: to resize the image to
		//@param: respect: should the resize be done with respect to the images dimension
		//@return: string encoding of an html image element discribing the pic to fetch.
		//this depends on the fetchmemberpic.php file in processing.
		public function getMemberPic($height = NULL, $width = NULL, $quality = NULL, $respect = NULL)
		{
			$sql = sprintf("SELECT PhotoFormat, Avatar FROM member_profile WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				if (!empty($row['PhotoFormat']))
				{
					$imgdef = '<img src="../processing/fetchmemberpic.php';
					if (isset($height) || isset($width) || isset($quality) || isset($respect))
					{
						$imgdef .= "?";
						if (isset($height))
							$imgdef .= "h=$height";
						
						if (strpos($imgdef, "h="))
							$imgdef .= "&";
							
						if (isset($width))
							$imgdef .= "w=$height";
						
						if (strpos($imgdef, "w="))
							$imgdef .= "&";
							
						if (isset($quality))
							$imgdef .= "q=$height";
						
						if (strpos($imgdef, "q="))
							$imgdef .= "&";
							
						if (isset($respect))
						{
							$respect = $respect ? "true" : "false";
							$imgdef .= "respect=$respect";
						}
					}
					
					$imgdef .= '" style="width:auto; height:auto" />';
					
					return $imgdef;
				}
				elseif (!empty($row['Avatar']))
				{
					//find the avatar
					$sql = sprintf("SELECT AvatarGenID FROM avatars WHERE AvatarGenID='%s'", $row['Avatar']);
					$avResult = $dbConn->query($sql);
					if ($avResult && $avResult->num_rows > 0)
					{
						$imgdef = '<img src="../processing/fetchavatarpic.php?avatar=' . $row['Avatar'] . '&';
						if (isset($height) || isset($width) || isset($quality) || isset($respect))
						{
							if (isset($height))
								$imgdef .= "h=$height";
							
							if (strpos($imgdef, "h="))
								$imgdef .= "&";
								
							if (isset($width))
								$imgdef .= "w=$height";
							
							if (strpos($imgdef, "w="))
								$imgdef .= "&";
								
							if (isset($quality))
								$imgdef .= "q=$height";
							
							if (strpos($imgdef, "q="))
								$imgdef .= "&";
								
							if (isset($respect))
							{
								$respect = $respect ? "true" : "false";
								$imgdef .= "respect=$respect";
							}
						}
						
						$imgdef .= '" style="width:auto; height:auto" />';
						
						return $imgdef;
					}
				}
			}
			
			return 0;
		}
		
		//sends friend request
		public function sendFriendRequest($memgenid, $message)
		{
			$sentRequest = FALSE;
			if (isset($memgenid, $message))
			{
				$fieldExists = FALSE;
				$sql = sprintf("SELECT FriendsList, Requests FROM friendship WHERE MemGenID='%s'", $memgenid);
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					//add the user friendship field
					$sql = sprintf("INSERT INTO friendship (MemGenID) VALUES('%s')", $memgenid);
					$this->mDBConn->query($sql);
					if ( $this->mDBConn->affected_rows > 0)
					{
						$fieldExists = TRUE;
					}
				}
				else
					$fieldExists = TRUE;
				
				if ($fieldExists)
				{
					//add this request. make sure it doesnt exist and is not friend.
					$sql = sprintf("SELECT FriendsList, Requests FROM friendship WHERE MemGenID='%s'", $memgenid);
					$result =  $this->mDBConn->query($sql);
					//check friend list.
					$row = $result->fetch_array();
					$friendlist = $row['FriendsList'];
					$requestlist = $row['Requests'];
					if (!empty($friendlist))
					{
						//check friend list
						$friendlist = explode(',', $friendlist);
						foreach ($friendlist as $friend)
						{
							if (!empty($friend))
							{
								if ($friend == $this->mMemberID)
								{
									//if i am a friend already
									$sentRequest = TRUE;
									break;
								}
							}
						}
					}
					
					//not friend
					if (!$sentRequest)
					{
						//add to request table
						//check friend list
						$requests = explode('#', $requestlist);
						foreach ($requests as $value)
						{
							if (!empty($value))
							{
								$reqJson = json_decode($value);
								if ($reqJson && property_exists($reqJson, "memgenid"))
								{
									if ($reqJson->memgenid == $this->mMemberID)
									{
										//if i am a friend already
										$sentRequest = TRUE;
										break;
									}
								}
							}
						}
						
						//add to request table after not found in there
						if (!$sentRequest)
						{
							$requestlist .= sprintf('{"memgenid": "%s", "datetime": "%s", "message": "%s"}#', $this->mMemberID, date("Y-m-j H:i:s"), $message);
							$sql = sprintf("UPDATE friendship SET Requests='%s' WHERE MemGenID='%s'", $requestlist, $memgenid);
							$this->mDBConn->query($sql);
							if ($this->mDBConn->affected_rows > 0)
								$sentRequest = TRUE;
						}
					}
				}
			}
			
			return $sentRequest;
		}
		
		//checks if a member is friend
		public function isMyFriend($memgenid)
		{
			$isMyFriend = FALSE;
			$sql = sprintf("SELECT FriendsList FROM friendship WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$friendlist = $row['FriendsList'];
				if (!empty($friendlist))
				{
					$friendlist = explode(',', $row['FriendsList']);
					foreach ($friendlist as $friend)
						if (!empty($friend))
							if ($friend == $memgenid)
							{
								$isMyFriend = TRUE;
								break;
							}
				}
			}
			
			return $isMyFriend;
		}
		
		//checks if i have made request to this member and it's pending
		public function isRequestPending($memgenid)
		{
			$isPending = FALSE;
			$sql = "SELECT Requests FROM friendship WHERE MemGenID='$memgenid'";
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$requests = $row['Requests'];
				if (!empty($requests))
				{
					$requests = explode('#', $requests);
					foreach ($requests as $value)
					{
						if (!empty($value))
						{
							$json = json_decode($value);
							if ($json && property_exists($json, "memgenid"))
							{
								if ($this->mMemberID == $json->memgenid)
								{
									$isPending = TRUE;
									break;
								}
							}
						}
					}
				}
			}
			
			return $isPending;
		}
		
		//gets friend requests
		public function getFriendRequests()
		{
			$requests = array("rtable"=>"", "rcount"=>0);
			$sql = sprintf("SELECT Requests FROM friendship WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				if (!empty($row['Requests']))
				{
					$rtable = explode('#', $row['Requests']);
					$rcount = 0;
					foreach ($rtable as $value)
						if (!empty($value))
							$rcount++;
					
					$requests['rtable'] = $row['Requests'];
					$requests['rcount'] = $rcount;
				}
			}
			
			return $requests;
		}
		
		//approve a friendship request
		public function approveFriendRequest($friendid)
		{
			try
			{
				//check for member
				$sql = "SELECT MemGenID FROM members WHERE MemGenID='$friendid'";
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows > 0)
				{
					$friendslist = $this->getFriends();
					if ($friendslist['fcount'] > 0 && array_key_exists($friendid, $friendslist))
						return TRUE; //already a friend.
						
					$requests = $this->getFriendRequests();
					if ($requests['rcount'] > 0)
					{
						//find this friends request
						$rtable = $requests['rtable'];
						$rtable = explode('#', $rtable);
						$friendrequest = NULL;
						foreach ($rtable as $value)
						{
							if (!empty($value))
							{
								$json = json_decode($value);
								if ($json && property_exists($json, "memgenid"))
								{
									if ($json->memgenid == $friendid)
									{
										$friendrequest = $json;
										break;
									}
								}
							}
						}
						
						if ($friendrequest)
						{
							//remove from request table
							$this->removeFriendRequest($friendrequest->memgenid);
							//add to my friend list
							return $this->addFriend($friendid);
						}
					}
				}
			}
			catch (Exception $ex)
			{
				
			}
			
			return FALSE;
		}
		
		//adds a friend
		public function addFriend($friendid)
		{
			//create my friendship record
			$sql = sprintf("SELECT MemGenID FROM friendship WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows == 0)
			{
				$sql = sprintf("INSERT INTO friendship (MemGenID) VALUES('%s')", $this->mMemberID);
				$this->mDBConn->query($sql);
				if ($this->mDBConn->affected_rows == 0)
					return FALSE;
			}
			
			//check for member
			$sql = "SELECT MemGenID FROM members WHERE MemGenID='$friendid'";
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$friendslist = $this->getFriends();
				if ($friendslist['fcount'] > 0 && array_key_exists($friendid, $friendslist))
					return TRUE; //already a friend.
				
				$newFriendsList = "";
				foreach ($friendslist as $key=>$value)
					if (strcasecmp($key, "fcount") != 0)
						$newFriendsList .= $key . ",";
				
				$newFriendsList .= "$friendid,";
				
				$sql = sprintf("UPDATE friendship SET FriendsList='%s' WHERE MemGenID='%s'", $newFriendsList, $this->mMemberID);
				$this->mDBConn->query($sql);
				
				return TRUE;
			}
			
			return FALSE;
		}
		
		//removes a request for friendship
		public function removeFriendRequest($friendid)
		{
			$requests = $this->getFriendRequests();
			if ($requests['rcount'] > 0)
			{
				$rtable = $requests['rtable'];
				$rtable = explode('#', $rtable);
				foreach ($rtable as $value)
				{
					if (!empty($value))
					{
						$json = json_decode($value);
						if ($json && property_exists($json, "memgenid"))
						{
							if ($json->memgenid == $friendid)
							{
								$friendrequest = $value;
								break;
							}
						}
					}
				}
				
				//remove from table
				$requests['rtable'] = str_replace($friendrequest . "#", '', $requests['rtable']); 

				$sql = sprintf("UPDATE friendship SET Requests='%s' WHERE MemGenID='%s'", $requests['rtable'], $this->mMemberID);
				$this->mDBConn->query($sql);
			}
		}
		
		//gets the members id
		public function getID()
		{
			return $this->mMemberID;
		}
		
		//gets friends
		public function getFriends()
		{
			$friends = array("fcount"=>0);
			$sql = sprintf("SELECT FriendsList FROM friendship WHERE MemGenID='%s'", $this->mMemberID);

			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$friendsList = $row['FriendsList'];
				
				if (!empty($friendsList))
				{
					$friendsList = explode(',', $friendsList);
					$fcount = 0;
					foreach ($friendsList as $value)
						if (!empty($value))
						{
							$friends["$value"] = "$value";
							$fcount++;
						}
						
					$friends["fcount"] = $fcount;
				}
			}
			
			return $friends;
		}
		
		//get week peekers for member
		//@param week: the week. current week is 7days from today so set as 1, last 2weeks is 7 days + 7 days which is 2 week
		//@limit: the number of returned results.  zero means not limit and will return everything
		//@return : array of key, value where key is the peeker id and value is the total peek count for that peeker.
		//also you can check the total number of peekers using the key weekPeekersTotal on the array that's returned
		public function getWeekPeekers($week, $limit = 0)
		{
			$peekersOfWeek = array();
			$secondsInSevenDays = 3600 * 24 * 7 * $week; //7 days represented as seconds.
			$toadyInSeconds = time();
			$lastWeekInSeconds = $toadyInSeconds - $secondsInSevenDays;
			
			$sql = sprintf("SELECT PeekersList FROM peeker WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				if (!empty($row['PeekersList']))
				{
					$peekerlist = explode('#', $row['PeekersList']);
					
					foreach ($peekerlist as $value)
						if (!empty($value))
						{
							$json = json_decode($value);
							if ($json && property_exists($json, "peeker"))
							{
								$pdatesec = strtotime($json->pdate);
								if ($pdatesec <= time() && $pdatesec >= $lastWeekInSeconds)
									if (!array_key_exists($json->peeker, $peekersOfWeek))
										$peekersOfWeek[$json->peeker] += $json->pcount;
									else
										$peekersOfWeek[$json->peeker] = $json->pcount;
							}
						}
					
					//order by highest
					$orderedSet = array();
					while (count($peekersOfWeek))
					{
						$keys = array_keys($peekersOfWeek);
						$highestCount = 0;
						$hightestKey = 0;
						$index = NULL;
						for ($j = 0; $j < count($peekersOfWeek); $j++)
						{
							if ($peekersOfWeek[$keys[$j]] > $highestCount)
							{
								$highestCount = $peekersOfWeek[$keys[$j]];
								$hightestKey = $keys[$j];
								$index = $j;
							}
						}
						
						if (isset($index)) //slice at index
						{
							//get data from peek
							$peekerlist = explode('#', $row['PeekersList']);
							$lastPeekDate = "0000-00-00";
							foreach ($peekerlist as $value)
								if (!empty($value))
								{
									$json = json_decode($value);
									if ($json && property_exists($json, "peeker"))
										if (strcmp($json->pdate, $lastPeekDate))
											$lastPeekDate = $json->pdate;
								}
							
							
							$orderedSet[$hightestKey] = sprintf('{"pcount": "%s", "lastpeeked": "%s"}', $highestCount, $lastPeekDate);
							array_splice(&$peekersOfWeek, $index, 1);
						}
					}
					
					$peekersOfWeek = $orderedSet;
					
					if ($limit > 0 && count($orderedSet) > $limit)
					{
						//pop $limit
						$peekersOfWeek = array_slice($peekersOfWeek, 0, $limit);
					}	
					
					$peekersOfWeek['weekPeekersTotal'] = count($orderedSet);
				}
			}
			
			return $peekersOfWeek;
		}
		
		
		//used to send message to a member.
		public function sendMessageTo($memberid, $message)
		{
			$messageSent = FALSE;
			$message = str_replace("\n", "", $message);
			$message = str_replace("'", "&rsquo;", $message);
			
			if ($memberid == $this->mMemberID)
				return FALSE;
				
			$sql = sprintf("SELECT MemGenID FROM members WHERE MemGenID='%s'", $memberid);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				//check for inbox
				//inbox setup
				$sql = "SELECT MemGenID, Messages FROM msginbox WHERE MemGenID='$memberid'";
				$result = $this->mDBConn->query($sql);
				$messages = "";
				if ($result && $result->num_rows == 0)
				{
					$sql = "INSERT INTO msginbox (MemGenID) VALUES('$memberid')";
					$this->mDBConn->query($sql);
				}
				else
				{
					$row = $result->fetch_array();
					$messages = $row['Messages'];
				}
				
				//send message
				$dateSent = time();
				$messageID = md5($dateSent);
				$messageFormat = sprintf('{"from": "%s", "to": "%s", "date": "%s", "message": "%s", "messageid":"%s", "oldmessage": "%s"}', $this->mMemberID, $memberid, $dateSent, $message, $messageID, "null") . "#";
				$sql = sprintf("UPDATE msginbox SET Messages='$messages%s' WHERE MemGenID='%s'", $messageFormat, $memberid);
				
				$this->mDBConn->query($sql);
				$messageSent = TRUE;
				//update sent messages.
				//setup sent record
				$sql = sprintf("SELECT MemGenID, Messages FROM msgsent WHERE MemGenID='%s'", $this->mMemberID);
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows == 0)
				{
					$sql = sprintf("INSERT INTO msgsent (MemGenID) VALUES('%s')", $this->mMemberID);
					$this->mDBConn->query($sql);
				}
				else
				{
					$row = $result->fetch_array();
					$messages = $row['Messages'];
				}
				
				//save in sent record
				$messageFormat = sprintf('{"from": "%s", "to": "%s", "date": "%s", "message": "%s", "messageid":"%s", "oldmessage": "%s"}', $this->mMemberID, $memberid, $dateSent, $message, $messageID, "null") . "#";
				
				$sql = sprintf("UPDATE msgsent SET Messages='$messages%s' WHERE MemGenID='%s'", $messageFormat, $this->mMemberID);
				
				$this->mDBConn->query($sql);
			}
			
			return $messageSent;
		}
		
		//used to get inbox messages for the user
		//@return: the collection of messages in inbox.
		public function getInboxMessages()
		{
			$messages = array();
			$sql = sprintf("SELECT MemGenID, Messages FROM msginbox WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				if (!empty($row['Messages']))
				{
					$messageCollection = explode('#', $row['Messages']);
					foreach ($messageCollection as $value)
					{
						if (!empty($value))
						{
							$json = json_decode($value);
							if ($json && property_exists($json, "messageid"))
							{
								$messages[$json->messageid] = $value;
							}
						}
					}
				}
			}
			
			return $messages;
		}
		
		//used to get send messages from the user
		//@return: the collection of messages in inbox.
		public function getSentMessages()
		{
			$messages = array();
			$sql = sprintf("SELECT MemGenID, Messages FROM msgsent WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				
				if (!empty($row['Messages']))
				{
					$messageCollection = explode('#', $row['Messages']);
					
					foreach ($messageCollection as $value)
					{
						if (!empty($value))
						{
							$json = json_decode($value);
							
							if ($json && property_exists($json, "messageid"))
							{
								$messages[$json->messageid] = $value;
							}
						}
					}
				}
			}
			
			return $messages;
		}
		
		//used to delete messages from inbox.
		public function deleteInboxMessage($msgid)
		{
			$deleted = FALSE;
			$sql = sprintf("SELECT Messages FROM msginbox WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$messages = $row['Messages'];
				$newMessageList = "";
				if (!empty($messages))
				{
					$messages = explode('#', $messages);
					foreach ($messages as $value)
					{
						if (!empty($value))
						{
							$json = json_decode($value);
							if ($json && property_exists($json, "messageid"))
								if (strcmp($json->messageid, $msgid) != 0)
									$newMessageList .= $value . "#";
						}
					}
					
					$sql = sprintf("UPDATE msginbox SET Messages='%s' WHERE MemGenID='%s'", $newMessageList, $this->mMemberID);
					$this->mDBConn->query($sql);
					$deleted = TRUE;
				}
			}
			
			return $deleted;
		}
		
		//used to delete messages from sent.
		public function deleteSentMessage($msgid)
		{
			$deleted = FALSE;
			$sql = sprintf("SELECT Messages FROM msgsent WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$row = $result->fetch_array();
				$messages = $row['Messages'];

				$newMessageList = "";
				if (!empty($messages))
				{
					$messages = explode('#', $messages);
					foreach ($messages as $value)
					{
						if (!empty($value))
						{
							$json = json_decode($value);
							if ($json && property_exists($json, "messageid"))
								if (strcmp($json->messageid, $msgid) != 0)
									$newMessageList .= $value . "#";
						}
					}
					
					$sql = sprintf("UPDATE msgsent SET Messages='%s' WHERE MemGenID='%s'", $newMessageList, $this->mMemberID);
					$this->mDBConn->query($sql);
					
					$deleted = TRUE;
				}
			}
			
			return $deleted;
		}
		
		//used to get ratings for this member
		public function getMyRatings()
		{
			$ratings = array();
			$sql = sprintf("SELECT RatingTable FROM member_rating WHERE MemGenID='%s'", $this->mMemberID);
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				//has been rated
				$row = $result->fetch_array();
				$ratingTable = $row['RatingTable'];
				$ratingTable = explode('#', $ratingTable);
				if (count($ratingTable))
					foreach ($ratingTable as $value)
					{
						$ratingJson = json_decode($value);
						if ($ratingJson && property_exists($ratingJson, "memgenid"))
							if (array_key_exists($ratingJson->memgenid, $ratings))
								$ratings[$ratingJson->memgenid] .= $value . "#";
							else
								$ratings[$ratingJson->memgenid] = $value . "#";
					}
			}
			
			return $ratings;
		}
		
		private $mMemberID = NULL;
		private $mDBConn = NULL;
		private $mProfile = NULL; //json string.
	}
?>