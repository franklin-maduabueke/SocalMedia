<?php
	//this class performs global operations on the site so i named it as such
	class IWanShokoto{
		function __construct($dbConn)
		{
			if (isset($dbConn) && is_a($dbConn, "mysqli") && $dbConn->ping())
			{
				$this->mDBConn = $dbConn;
			}
			else
				throw new Exception("Database connection object invalid");
		}
		
		//used to find members that match a given criteria
		//@param username: the username of the member
		//@param agerange(string): agefrom#ageto of the search
		//@param location(int): state id
		//@param intrests(string): genid of intrests the members wants are the string "ANYTHING"
		//@param gender: the member's gender
		//@param hasphoto: member photo check on search
		public function findMembersMatch($username, $agerange, $location, $intrests, $gender=0, $hasphoto = FALSE, $limit = 0, $offset = 0)
		{
			$matchCollection = array();
			$sql = "SELECT m.MemGenID, m.Username, FullName, DOB, Gender, Seeking, StateName AS Location, LookingFor, Relationship, IntrestedIn FROM member_profile AS mp LEFT JOIN members AS m ON mp.MemGenID=m.MemGenID LEFT JOIN locations ON mp.Location=locations.LTID";
			
			if (isset($username) || isset($agerange) || isset($location) || isset($intrests))
				$sql .= " WHERE ";
			
			if (isset($username))
				$sql .= " m.Username LIKE '$username%' ";
			
			if (isset($agerange) && is_string($agerange) && !is_bool(strpos($agerange, "#")))
			{
				$ageranges = explode("#", $agerange);
				$agefrom = $ageranges[0];
				$ageto = $ageranges[1];
				
				if (isset($username))
					$sql .= " AND ";
					
				$currentYear = date("Y");
				$sql .= sprintf(" ($currentYear - YEAR(mp.DOB)) >= $agefrom AND ($currentYear - YEAR(mp.DOB)) <= $ageto ");
			}
			
			if (isset($location) && $location != "0")
			{
				if (isset($username) || (isset($agerange) && is_string($agerange) && !is_bool(strpos($agerange, "#"))))
					$sql .= " AND ";
				
				$sql .= " mp.Location=$location ";
			}
			
			if (isset($intrests) && !empty($intrests))
			{
				if (isset($username) || (isset($agerange) && is_string($agerange) && !is_bool(strpos($agerange, "#"))) || isset($location))
				{
					$sql .= " AND ";
				}
				
				if (strcmp($intrests, "ANYTHING") == 0)
				{
					$sql .= "(mp.IntrestedIn LIKE '%%' OR mp.IntrestedIn IS NULL)";
				}
				elseif (!empty($intrests))
				{
					$sql .= " ( ";
					$intrests = explode(',', $intrests);
					foreach ($intrests as $idx=>$value)
					{
						if ($idx > 0)
							$sql .= " OR ";
							
						if (!empty($value))
							$sql .= " mp.IntrestedIn LIKE '%$value%' ";
					}
					$sql .= " ) ";
				}
			}
			
			if ($hasphoto)
			{
				if (isset($username) || (isset($agerange) && is_string($agerange) && !is_bool(strpos($agerange, "#"))) || isset($location) || isset($intrests))
					$sql .= " AND ";
				
				$sql .= " (PhotoFormat IS NOT NULL OR Avatar IS NOT NULL) ";
			}
			
			if (isset($gender))
			{
				if ($gender != 0)
				{
					if (isset($username) || (isset($agerange) && is_string($agerange) && !is_bool(strpos($agerange, "#"))) || isset($location) || isset($intrests) || isset($hasphoto))
					$sql .= " AND ";
					
					$sql .= " mp.Gender=$gender";
				}
			}
			
			if ($limit > 0)
				$sql .= " LIMIT $limit OFFSET $offset ";
				
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				for (;$row = $result->fetch_array();)
				{
					$matchCollection[$row['MemGenID']] = sprintf('{"memgenid": "%s", "username": "%s", "fullname": "%s", "dob": "%s", "gender": "%s", "seeking": "%s", "location": "%s", "lookingfor": "%s", "relationship": "%s", "intrests": "%s"}', $row['MemGenID'], $row['Username'], $row['FullName'], $row['DOB'], $row['Gender'], $row['Seeking'], $row['Location'], $row['LookingFor'], $row['Relationship'], $row['IntrestedIn']);
				}
			}
			
			//echo $sql;
			
			return $matchCollection;
		}
		
		//gets the rating types
		public function getRatingTypes()
		{
			$ratingTypes = array();
			$sql = "SELECT RatingGenID, RatingName FROM rating";
			$result = $this->mDBConn->query($sql);
			
			if ($result && $result->num_rows > 0)
				for (;$row = $result->fetch_array();)
					$ratingTypes[$row['RatingGenID']] = sprintf('{"ratinggenid": "%s", "ratingname": "%s"}', $row['RatingGenID'], $row['RatingName']);
				
			return $ratingTypes;
		}
		
		//used to rate a member
		//@param $mark: the mark from 0-10 to rate the member
		//Json format in db: {memgenid, ratedbyid, score, date}
		public function rateMember($judgeid, $contestantid, $ratingtypeid, $mark)
		{
			$ratingDone = FALSE;
			$ratingTypes = $this->getRatingTypes();
			//find members
			if (isset($judgeid, $contestantid, $ratingtypeid, $mark) && array_key_exists($ratingtypeid, $ratingTypes) && is_numeric($mark))
			{
				$sql = "SELECT MemGenID FROM members WHERE MemGenID='$judgeid'";
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows > 0)
				{
					$sql = "SELECT MemGenID FROM members WHERE MemGenID='$contestantid'";
					$result = $this->mDBConn->query($sql);
					if ($result && $result->num_rows > 0)
					{
						//get member rating table of create record
						$available = FALSE;
						$sql = "SELECT RatingTable FROM member_rating WHERE MemGenID='$contestantid'";
						$result = $this->mDBConn->query($sql);
						if ($result && $result->num_rows == 0)
						{
							$sql = "INSERT INTO member_rating (MemGenID) VALUES('$contestantid')";
							$result = $this->mDBConn->query($sql);
							if ($this->mDBConn->affected_rows > 0)
								$available = TRUE;
						}
						else
							$available = TRUE;
						
						if ($available)
						{
							//rating table available
							$sql = "SELECT RatingTable FROM member_rating WHERE MemGenID='$contestantid'";
							$result = $this->mDBConn->query($sql);
							$row = $result->fetch_array();
							$contestantRatings = $row['RatingTable'];
							$oldRatings = $contestantRatings;
							
							if (!empty($contestantRatings))
							{
								//find the previous rating on this type from this judge
								$contestantRatings = explode('#', $contestantRatings);
								if (count($contestantRatings))
								{
									foreach ($contestantRatings as $value)
									{
										$ratingJson = json_decode($value);
										if ($ratingJson && property_exists($ratingJson, "memgenid"))
										{
											//echo "<br/>Good json " . $ratingJson->ratedbyid . " = $ratingtypeid";
											if (strcmp($ratingJson->memgenid, $judgeid) == 0 && strcmp($ratingJson->ratedbyid, $ratingtypeid) == 0)
											{
												$newRatings = str_replace($value . "#", "", $oldRatings);
												$ratingJson->score = $mark;
												$newRatings .= sprintf('{"memgenid": "%s", "ratedbyid": "%s", "score": "%s", "date": "%s"}#', $ratingJson->memgenid, $ratingJson->ratedbyid, $ratingJson->score, time());
												
												$sql = "UPDATE member_rating SET RatingTable='$newRatings' WHERE MemGenID='$contestantid'";
												$this->mDBConn->query($sql);
												return TRUE;
											}
										}
									}
									
									//rating from this judge on this type does not exist so create
									$newRatings = $oldRatings;
									$newRatings .= sprintf('{"memgenid": "%s", "ratedbyid": "%s", "score": "%s", "date": "%s"}#', $judgeid, $ratingtypeid, $mark, time());
									
									$sql = "UPDATE member_rating SET RatingTable='$newRatings' WHERE MemGenID='$contestantid'";
									$this->mDBConn->query($sql);
									return TRUE;
								}
							}
							else
							{
								$newRatings = sprintf('{"memgenid": "%s", "ratedbyid": "%s", "score": "%s", "date": "%s"}#', $judgeid, $ratingtypeid, $mark, time());
									
								$sql = "UPDATE member_rating SET RatingTable='$newRatings' WHERE MemGenID='$contestantid'";
								$this->mDBConn->query($sql);
								return TRUE;
							}
						}
					}
				}
			}
			
			return $ratingDone;
		}
		
		//gets the current hottest members on the iwanshokoto.com
		//@return array of collection
		//members are rated hot by score card, then the number of profile views they've had overtime
		//by how long they have been members
		public function getHottestMembers()
		{
			$hottestCollection = array();
			
			//get ratings table average for each member
			$sql = "SELECT MemGenID, RatingTable FROM member_rating";
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				//ratings in rating table are json string comma separated.
				for (;$row = $result->fetch_array();)
				{
					$memgenid = $row['MemGenID'];
					$ratingtable = $row['RatingTable'];
					if (!empty($ratingtable))
					{
						$ratingtable = explode(',', $ratingtable);
						$totalScore = 0;
						$totalJudges = 0;
						foreach ($ratingtable as $value)
						{
							//value is json formated
							if (!empty($value))
							{
								$json = json_decode($value);
								if ($json && property_exists($json, "memgenid"))
								{
									$totalScore += $json->score;
									$totalJudges++;
								}
							}
						}
						
						$averageMark = 0;
						if ($totalJudges > 0)
							$averageMark = floor($totalScore / $totalJudges);
							
						$hottestCollection[$memgenid] = $averageMark;
					}
				}
			}
			
			//get ratings average for profile view.
			$sql = "SELECT MemGenID, PeekersList FROM peeker";
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				//ratings in rating table are json string comma separated.
				for (;$row = $result->fetch_array();)
				{
					$memgenid = $row['MemGenID'];
					$peekerslist = $row['PeekersList'];
					if (!empty($peekerslist))
					{
						$peekerslist = explode(',', $peekerslist);
						$totalPeeksForWeek = 0;
						
						foreach ($peekerslist as $value) //value is member genid
							if (!empty($value) && strlen($value) >= 20) //length of a genid for a member
								$totalPeeksForWeek++;
						
						$hottestCollection[$memgenid] += $totalPeeksForWeek;
					}
				}
			}
			
			if (count($hottestCollection) > 0)
			{
				//the order by highest score
				$hottestOrder = array();
				$keys = array_keys($hottestCollection);
				
				for ($i = 0; $i < count($hottestCollection); $i++)
				{
					$highestKey = 0;
					$highestScore = 0;
					
					for ($j = 0; $j < count($hottestCollection); $j++)
					{
						if (array_key_exists($keys[$j], $hottestOrder))
							continue;
							
						//echo "$j, $hightestScore" . $keys[$j] . "<br/>";
						if ($highestScore <= $hottestCollection[$keys[$j]])
						{
							$highestScore = $hottestCollection[$keys[$j]];
							$highestKey = $keys[$j];
						}
					}
					
					if ($highestKey != 0)
						$hottestOrder[$highestKey] = $highestScore;
				}
				
				
				foreach ($hottestOrder as $key=>$value)
				{
					$sql = "SELECT m.MemGenID, m.Username, loc.StateName, (YEAR(CURRENT_DATE()) - YEAR(mp.DOB)) AS MemAge, mp.Gender FROM member_profile AS mp LEFT JOIN members AS m ON mp.MemGenID=m.MemGenID LEFT JOIN locations AS loc ON mp.Location=loc.LTID WHERE mp.MemGenID='$key'";
					$result = $this->mDBConn->query($sql);
					$row = $result->fetch_array();
					$hottestCollection[$row['MemGenID']] = sprintf('{"memgenid": "%s", "username": "%s", "location": "%s", "age": "%s", "gender": "%s"}', $row['MemGenID'], $row['Username'], $row['StateName'], $row['MemAge'], $row['Gender']);
				}
			}
			else
			{ 
				//just get the members we have currently so we can show something
				$sql = "SELECT m.MemGenID, m.Username, loc.StateName, (YEAR(CURRENT_DATE()) - YEAR(mp.DOB)) AS MemAge, mp.Gender FROM member_profile AS mp LEFT JOIN members AS m ON mp.MemGenID=m.MemGenID LEFT JOIN locations AS loc ON mp.Location=loc.LTID ORDER BY DOR DESC";
				$result = $this->mDBConn->query($sql);
				
				if ($result && $result->num_rows > 0)
					for (;$row = $result->fetch_array();)
						$hottestCollection[$row['MemGenID']] = sprintf('{"memgenid": "%s", "username": "%s", "location": "%s", "age": "%s", "gender": "%s"}', $row['MemGenID'], $row['Username'], $row['StateName'], $row['MemAge'], $row['Gender']);
			}
			
			return $hottestCollection;
		}
		
		//get total number of members
		//@return: the number of members
		public function getTotalMembers()
		{
			$sql = "SELECT COUNT(MemGenID) AS MemberCount FROM members";
			$result = $this->mDBConn->query($sql);
			$row = $result->fetch_array();
			return $row['MemberCount'];
		}
		
		//gets the intrests table data
		public function getIntrests()
		{
			$intrests = array();
			$sql = "SELECT IntrestGenID, IntrestName FROM intrests";
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
				for (;$row = $result->fetch_array();)
					$intrests[$row['IntrestGenID']] = sprintf('{"intrestgenid": "%s", "intrestname": "%s"}', $row['IntrestGenID'], $row['IntrestName']);
			
			return $intrests;
		}
		
		//set peeker on member
		public function setMemberPeeker($peekerid, $memid)
		{
			$peekerSet = FALSE;
			if (isset($peekerid, $memid) && strcmp($peekerid, $memid) != 0)
			{
				$sql = "SELECT MemGenID FROM members WHERE MemGenID='$peekerid'";
				$result = $this->mDBConn->query($sql);
				if ($result && $result->num_rows > 0)
				{
					$sql = "SELECT MemGenID FROM members WHERE MemGenID='$memid'";
					$result = $this->mDBConn->query($sql);
					if ($result && $result->num_rows > 0)
					{
						$sql = "SELECT MemGenID, PeekersList FROM peeker WHERE MemGenID='$memid'";
						$result = $this->mDBConn->query($sql);
						$avaliableRecord = FALSE;
						if ($result && $result->num_rows == 0)
						{
							//add to member record to peeker table
							$sql = "INSERT INTO peeker (MemGenID) VALUES('$memid')";
							$this->mDBConn->query($sql);
							
							if ($this->mDBConn->affected_rows > 0)
								$avaliableRecord = TRUE;
						}
						else
							$avaliableRecord = TRUE;
						
						if ($avaliableRecord)
						{
							//echo "Record available...<br/>";
							$sql = "SELECT MemGenID, PeekersList FROM peeker WHERE MemGenID='$memid'";
							$result = $this->mDBConn->query($sql);
							if ($result && $result->num_rows > 0)
							{
								$row = $result->fetch_array();
								$peekerList = $row['PeekersList'];
								//check for list for today
								if (!empty($peekerList))
								{
									$peekers = explode('#', $peekerList);
									$todayEntryForPeeker = "";
									
									foreach ($peekers as $value)
									{
										if (!empty($value))
										{
											$peekerJson = json_decode($value);
											if ($peekerJson && property_exists($peekerJson, "peeker"))
											{
												if ($peekerid == $peekerJson->peeker && date("Y-m-j") == $peekerJson->pdate)
												{
													$todayEntryForPeeker = $value;
													break;
												}
											}
										}
									}
									
									if (!empty($todayEntryForPeeker))
									{
										//adjust and serve
										$peekerJson = json_decode($todayEntryForPeeker);
										$peekerJson->pcount++;
										$newpeekerFormat = sprintf('{"peeker": "%s", "pdate": "%s", "pcount": "%s"}', $peekerJson->peeker, $peekerJson->pdate, $peekerJson->pcount);
										
										$peekerList = str_replace($todayEntryForPeeker, $newpeekerFormat, $peekerList);
										$sql = sprintf("UPDATE peeker SET PeekersList='%s' WHERE MemGenID='%s'", $peekerList, $memid);
										$this->mDBConn->query($sql);
										if ($this->mDBConn->affected_rows > 0)
											$peekerSet = TRUE;
									}
									else
									{
										//no entry so enter
										$newpeeker = sprintf('{"peeker": "%s", "pdate": "%s", "pcount": "%s"}', $peekerid, date("Y-m-j"), 1);
										$peekerList = $peekerList . "#" . $newpeeker;
										$sql = sprintf("UPDATE peeker SET PeekersList='%s' WHERE MemGenID='%s'", $peekerList, $memid);
										$this->mDBConn->query($sql);
										if ($this->mDBConn->affected_rows > 0)
											$peekerSet = TRUE;
									}
								}
								else
								{
									//add this peeker
									$sql = sprintf("UPDATE peeker SET PeekersList='%s' WHERE MemGenID='%s'", sprintf('{"peeker": "%s", "pdate": "%s", "pcount": "%s"}', $peekerid, date("Y-m-j"), 1), $memid);
									$this->mDBConn->query($sql);
									if ($this->mDBConn->affected_rows > 0)
										$peekerSet = TRUE;
								}
							}
						}
					}
				}
			}
			
			return $peekerSet;
		}
		
		
		//checks if a members profile is complete
		public function isMemberProfileComplete($memid)
		{
			$isComplete = FALSE;
			$sql = "SELECT m.MemGenID, mp.DOB, mp.IntrestedIn, mp.SexOrientation, mp.Occupation, mp.Height, mp.Relationship, mp.FavQuotes, mp.CountryResidence, mp.PhotoFormat, mp.Avatar, mp.MyFairyTaleRomance, mp.BiggestAsset, mp.AboutMe FROM members AS m LEFT JOIN member_profile AS mp ON m.MemGenID=mp.MemGenID WHERE m.MemGenID='$memid'";
			$result = $this->mDBConn->query($sql);
			
			if ($result && $result->num_rows > 0)
			{
				//to be considered complete age, location, looking for, seeking, intrest
				$row = $result->fetch_array();
				if (empty($row['DOB']))
					return FALSE;
				if (empty($row['IntrestedIn']))
					return FALSE;
				if (strcmp($row['SexOrientation'], "UNDISCLOSED") == 0)
					return FALSE;
				if (empty($row['Occupation']))
					return FALSE;
				if (empty($row['Height']))
					return FALSE;
				if (empty($row['Relationship']))
					return FALSE;
				/*if (empty($row['FavQuotes']))
					return FALSE;*/
				if (empty($row['CountryResidence']))
					return FALSE;
				if (empty($row['PhotoFormat']) && empty($row['Avatar']))
					return FALSE;
				if (empty($row['MyFairyTaleRomance']))
					return FALSE;
				if (empty($row['BiggestAsset']))
					return FALSE;
				if (empty($row['AboutMe']))
					return FALSE;
					
				
				$isComplete = TRUE; 
			}
			
			return $isComplete;
		}
		
		//used to get members that are online.
		public function getOnlineMembers()
		{
			$onlineMembers = array();
			$sql = sprintf("SELECT MemGenID, LastLogin FROM members WHERE IsOnline<>0 AND IsOnline>%d", time());
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
				for (; $row = $result->fetch_array();)
					$onlineMembers[$row['MemGenID']] = $row['LastLogin'];
			
			return $onlineMembers;
		}
		
		//used to get members rated by online status, lastlogin, rating table, newest
		public function getMembersForShow()
		{
			$collection = array();
			$sql = "SELECT MemGenID, DOR, LastLogin FROM members ORDER BY DOR DESC"; //newest members first
			$result = $this->mDBConn->query($sql);
			if ($result && $result->num_rows > 0)
			{
				$onlineMembers = $this->getOnlineMembers();
				if (count($onlineMembers))
					$collection = $onlineMembers;
					
				for (; $row = $result->fetch_array();)
					if (!array_key_exists($row['MemGenID'], $collection))
						$collection[$row['MemGenID']] = $row['LastLogin'];
			}
			
			return $collection;
		}
		
		
		private $mDBConn = NULL;
	}
?>