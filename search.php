<?php
require 'header.php'; 
require 'nav.php';
require 'functions.php';
?>

<section>
	<div class="row">
		<div class="small-12 columns">
			<h2>Search Results</h2>

<?php
	$varErr = array();
	$varPrint = array();
	$sqlArgs = array();
	$pdoArgs = array();
	$sbox = $fname = $lname = "";
	
	if($_SERVER["REQUEST_METHOD"] == "GET") {
		$sbox1 = test_input($_GET["kw"]);
		$fname = test_input($_GET["fn"]);
		$lname1 = test_input($_GET["ln"]);
		$church1 = test_input($_GET["ch"]);
		$burial1 = test_input($_GET["bur"]);
		$yeartype = test_input($_GET["yt"]);
		$startmonth = test_input($_GET["smn"]);
		$startyear= test_input($_GET["syr"]);
		$endyear = test_input($_GET["eyr"]);
		$endmonth = test_input($_GET["emn"]);
		
		// 1: Keyword checking (from search box in navbar)
		if ($sbox1 !== "") {
			$varPrint['Search terms'] = $sbox1;
			$sbox = addslashes($sbox1);
			$wcount = str_word_count($sbox);
			$sbArray = array();
			$sqlKW2 = "";
			if ($wcount > 1) {
				$token = strtok($sbox, " ");
				for($g=1; $g<=$wcount; $g++) {
					$sbArray[$g] = $token;
					$token = strtok(" ");
				}
				foreach($sbArray as $words) {
					$keycount2 = stripos($words,"%");
					if($keycount2 > 0) {
						$sqlKW2 = "(LastName LIKE '".$words."' OR FirstMiddleName LIKE '".$words."' OR MaidenName LIKE '".$words."' OR OtherNames LIKE '".$words."' OR Church LIKE '".$words."' OR Burial LIKE '".$words."' OR Notes LIKE '".$words."') ";
						array_push($sqlArgs, $sqlKW2);
					} else {
						$sqlKW2 = "(LastName LIKE '%".$words."%' OR FirstMiddleName LIKE '%".$words."%' OR MaidenName LIKE '%".$words."%' OR OtherNames LIKE '%".$words."%' OR Church LIKE '%".$words."%' OR Burial LIKE '%".$words."%' OR Notes LIKE '%".$words."%') ";
						array_push($sqlArgs, $sqlKW2);
					}
				}
			} // end if word count > 1
			else {
				$keycount = stripos($sbox,"%");
				if ($keycount > 0) {
					$sqlKW = "(FirstMiddleName LIKE '".$sbox."' OR OtherNames LIKE '".$sbox."' OR LastName LIKE '".$sbox."' OR MaidenName LIKE '".$sbox."' OR Church LIKE '".$sbox."' OR Burial LIKE '".$sbox."' OR Notes LIKE '".$sbox."') ";
					$searchKW = $sbox;
					array_push($sqlArgs, $sqlKW);
				} else {
					$sqlKW = "(FirstMiddleName LIKE '%".$sbox."%' OR OtherNames LIKE '%".$sbox."%' OR LastName LIKE '%".$sbox."%' OR MaidenName LIKE '%".$sbox."%' OR Church LIKE '%".$sbox."%' OR Burial LIKE '%".$sbox."%' OR Notes LIKE '%".$sbox."%') ";
					$searchKW = "%".$sbox."%";
					array_push($sqlArgs, $sqlKW);
				}
			}
		}
		
		// 2: First Name checking
		if ($fname !== "") {
			$varPrint['First Name'] = $fname;
			$fncount = stripos($fname,"%");
			if ($fncount > 0) {
				$sqlFN = "(FirstMiddleName LIKE '".$fname."' OR OtherNames LIKE '".$fname."') ";
				$searchForename = $fname;
				array_push($sqlArgs, $sqlLN);
			} else {
				$sqlFN = "(FirstMiddleName LIKE '%".$fname."%' OR OtherNames LIKE '%".$fname."%') ";
				$searchForename = "%".$fname."%";
				array_push($sqlArgs, $sqlFN);
			}
		}
		
		// 3: Last Name checking
		if ($lname1 !== "") {
			$varPrint['Last Name'] = $lname1;
			$lname = addslashes($lname1);
			$lncount = stripos($lname,"%");
			if ($lncount > 0) {
				$sqlLN = "(LastName LIKE '".$lname."' OR MaidenName LIKE '".$lname."' OR OtherNames LIKE '".$lname."') ";
				$searchSurname = $lname;
				array_push($sqlArgs, $sqlLN);
			} else {
				$sqlLN = "(LastName LIKE '%".$lname."%' OR MaidenName LIKE '%".$lname."%' OR OtherNames LIKE '%".$lname."%') ";
				$searchSurname = "%".$lname."%";
				array_push($sqlArgs, $sqlLN);
			}
		}
		
		// 4: Church checking
		if ($church1 !== "") {
			$varPrint['Church'] = $church1;
			$searchChurch = $church1;
			$church = addslashes($church1);
			$chcount = stripos($church,"%");
			if ($chcount > 0) {
				$sqlChurch = "Church LIKE '".$church."' ";
				array_push($sqlArgs, $sqlChurch);
			} else {
				$sqlChurch = "Church LIKE '%".$church."%' ";
				array_push($sqlArgs, $sqlChurch);
			}
		} 
		
		// 5: Burial checking
		if ($burial1 !== "") {
			$varPrint['Burial'] = $burial1;
			$searchBurial = $burial1;
			$burial = addslashes($burial1);
			$cemcount = stripos($burial,"%");
			if ($cemcount > 0) {
				$sqlBurial = "Burial LIKE '".$burial."' ";
				array_push($sqlArgs, $sqlBurial);
			} else {
				$sqlBurial = "Burial LIKE '%".$burial."%' ";
				array_push($sqlArgs, $sqlBurial);
			}
		} 
		
		// 6: Publication Year(s) checking
		if (!preg_match("/^[a-zA-Z']*$/",$yeartype)) {
			$varErr['yeartype'] = "Year type must be one of the dropdown options."; 
		}
		if (!preg_match("/[0-9]{4}/",$startyear)) {
			$varErr['startyear'] = "Publication year must be exactly 4 numbers."; 
		} 
		if (!preg_match("/[0-9]{4}/",$endyear)) {
		$varErr['endyear'] = "Publication year must be exactly 4 numbers."; 
		}
		if($yeartype == "in" && $startyear !== "" && $varErr['yeartype'] == "" && $varErr['startyear'] == "") {
			if ($startmonth == "all") {
				$varPrint['Published In'] = $startyear;
				$sqlPub = "DatePublished LIKE '%".$startyear."%' ";
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				array_push($sqlArgs, $sqlPub); 
			} elseif($startmonth >= "01" && $startmonth <= 12) {
				$pubmonth = getmonth($startmonth);
				$varPrint['Published In'] = $pubmonth." ".$startyear;
				$firstdate = $startyear."-".$startmonth."-01";
				$enddate = formatdate("$startmonth", $startyear);
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished BETWEEN '".$firstdate."' AND '".$enddate."' ";
				array_push($sqlArgs, $sqlPub);
			}
		} elseif ($yeartype == "before" && $startyear !== "" && $varErr['yeartype'] == "" && $varErr['startyear'] == "") {
			if ($startmonth == "all") {
				$varPrint['Published Before'] = $startyear;
				$sqlPub = "DatePublished < '".$startyear."-01-01' ";
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				array_push($sqlArgs, $sqlPub);
			} elseif($startmonth >= "01" && $startmonth <= 12) {
				$pubmonth = getmonth($startmonth);
				$varPrint['Published After'] = $pubmonth." ".$startyear;
				$firstdate = $startyear."-".$startmonth."-01";
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished < '".$firstdate."' ";
				array_push($sqlArgs, $sqlPub);
			}
		} elseif ($yeartype == "after" && $startyear !== "" && $varErr['yeartype'] == "" && $varErr['startyear'] == "") {
			if ($startmonth == "all") {
				$varPrint['Published After'] = $startyear;
				$sqlPub = "DatePublished >= '".$startyear."-12-31' ";
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				array_push($sqlArgs, $sqlPub);
			} elseif($startmonth >= "01" && $startmonth <= 12) {
				$pubmonth = getmonth($startmonth);
				$varPrint['Published After'] = $pubmonth." ".$startyear;
				$enddate = formatdate("$startmonth", $startyear);
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished > '".$enddate."' ";
				array_push($sqlArgs, $sqlPub);
			}
		} elseif ($yeartype == "between" && $startyear !== "" && $endyear !== "" && $varErr['yeartype'] == "" && $varErr['startyear'] == "" && $varErr['endyear'] == "") {
			if ($startmonth == "all" && $endmonth == "all") {
				$varPrint['Published Between'] = $startyear." and ".$endyear;
				$sqlPub = "(DatePublished BETWEEN '".$startyear."-01-01' AND '".$endyear."-12-31') ";
				$searchStartYear = $startyear;
				$searchEndYear = $endyear;
				$searchDateType = $yeartype;
				array_push($sqlArgs, $sqlPub);
			} elseif ($startmonth == "all" && ($endmonth >= "01" && $endmonth <=12)) {
				$pubend = getmonth($endmonth);
				$varPrint['Published Between'] = $startyear." and ".$pubend." ".$endyear;
				$firstdate = $startyear."-01-01";
				$enddate = formatdate("$endmonth", $endyear);
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished BETWEEN '".$firstdate."' AND '".$enddate."' ";
				array_push($sqlArgs, $sqlPub);
			} elseif (($startmonth >= "01" && $startmonth <= 12) && $endmonth == "all") {
				$pubstart = getmonth($startmonth);
				$varPrint['Published Between'] = $pubstart." ".$startyear." and ".$endyear;
				$enddate = $endyear."-12-31";
				$firstdate = formatdate("$startmonth", $startyear);
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished BETWEEN '".$firstdate."' AND '".$enddate."' ";
				array_push($sqlArgs, $sqlPub);
			} elseif(($startmonth >= "01" && $startmonth <= 12) && ($endmonth >= "01" && $endmonth <= 12)) {
				$pubend = getmonth($endmonth);
				$pubstart = getmonth($startmonth);
				$varPrint['Published Between'] = $pubstart." ".$startyear." and ".$pubend." ".$endyear;
				$firstdate = $startyear."-".$startmonth."-01";
				$enddate = formatdate("$endmonth", $endyear);
				$searchStartYear = $startyear;
				$searchDateType = $yeartype;
				$sqlPub = "DatePublished BETWEEN '".$firstdate."' AND '".$enddate."' ";
				array_push($sqlArgs, $sqlPub);
			}
		}


		// search string compilations
		$x = count($sqlArgs);
		$sqlString = "";
		if ($x > 1) {
			$sqlString = $sqlArgs[0];
			for($i=1; $i<$x; $i++) {
				$sqlString .= " AND ".$sqlArgs[$i];
			}
		} elseif ($x == 1) {
			$sqlString = $sqlArgs[0];
		} 
				
		try {
			// Create connection
			require 'config.php';
			$dsn = 'mysql:host=' . $servername . ';dbname=' . $dbname;
			$dbh = new PDO($dsn, $user, $pass);			
			
			// COUNT SQL statement
			$sql = "SELECT COUNT(*) FROM Obituaries WHERE ".$sqlString;
			$stmt2 = $dbh->query($sql);
			$total = $stmt2->fetchColumn();
			
			// this part from http://php.net/manual/en/pdostatement.rowcount.php
			if ($total > 0) {
				
				// PAGINATION START
				$limit = 15;

				// How many pages will there be
				$pages = ceil($total / $limit);

				// What page are we currently on?
				$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
					'options' => array(
						'default'   => 1,
						'min_range' => 1,
					),
				)));

				// Calculate the offset for the query
				$offset = ($page - 1)  * $limit;

				// Some information to display to the user
				$start = $offset + 1;
				$end = min(($offset + $limit), $total);		

				echo "<div class=\"row align-top \" style=\"margin-top: 1.5rem; margin-bottom: 1.2rem;\"><div class=\"small-12 medium-4 columns\"><p>Displaying ".$start." &ndash; ".$end." of <span class=\"label success sterms\"><b>".$total."</b></span> results</p></div><div class=\"small-12 medium-8 text-right columns\">";
		
				$q = count($varPrint);
				if ($q > 0) {
					echo "<span class=\"text-right\">";
					foreach($varPrint as $key => $value) {
						echo $key.": "."<kbd style=\"padding: .4rem;\">".$value."</kbd> &nbsp; "; 
					}
					echo "</span>";
				}
				echo "</div></div>";

				/** STARTING SEARCH RESULTS **/
				// do something where if year is only input, order by year
				$churlen = strlen($varPrint['Church']);
				$burlen = strlen($varPrint['Burial']);
				$order = "LastName ASC, FirstMiddleName ASC ";
				if($searchStartYear !== "" && $searchDateType !== "") {
					if($searchForename == "" && $searchSurname == "" && $searchBurial == "" && $searchChurch == "" && $searchKW == "") {
						if ($searchDateType == "before") {
							$order = "DatePublished DESC, LastName ASC, FirstMiddleName ASC ";
						} elseif ($searchDateType == "after" || $searchDateType == "in" || ($searchDateType == "between" && $searchEndYear !== "")) {
							$order = "DatePublished ASC, LastName ASC, FirstMiddleName ASC ";
						} 
					}  else {
						$order = "LastName ASC, FirstMiddleName ASC, DatePublished ASC ";
					} 
				} 
				if ($churlen > 0) {
					if($searchForename == "" && $searchSurname == "" && ($burlen == 0)) {
						$order = "Church ASC, LastName ASC, FirstMiddleName ASC ";
					} elseif ($burlen > 0) {
						$order = "Church ASC, Burial ASC, LastName ASC, FirstMiddleName ASC ";
					}
				} elseif ($burlen > 0) {
					$order = "Burial ASC, LastName ASC, FirstMiddleName ASC ";
				} 
				
				$sql2 = "SELECT * FROM Obituaries WHERE ".$sqlString." ORDER BY ".$order." LIMIT ".$limit." OFFSET ".$offset;
				$mint = $dbh->query($sql2);
				$mint->setFetchMode(PDO::FETCH_ASSOC);
				$iterator = new IteratorIterator($mint);					

				echo "<div id=\"resultsBox\">";				
				
				/** DISPLAY RESULTS **/
				foreach ($iterator as $row) {
						// Showing brief results
						echo "<div class=\"results row align-middle\" id=\"result".$row["ObitID"]."\">";
						
						// printing the name
						echo "<div class=\"small-12 large-expand columns\">";
						if(is_null($row["LastName"]) && is_null($row["FirstMiddleName"])) {
							$printableName = "No name at all";
						} elseif (is_null($row["LastName"]) && !is_null($row["FirstMiddleName"])){
							$printableName = $row["FirstMiddleName"];
						} elseif (!is_null($row["LastName"]) && is_null($row["FirstMiddleName"])){
							$printableName = $row["LastName"];
						} else {
							$printableName = $row["LastName"].", ".$row["FirstMiddleName"];
						}
						// each result is a form that sends user to page with full obit info
						echo "<form action=\"obit.php\" id=\"\" method=\"get\" onsubmit=\"stringCleaning()\">";
							echo "<input type=\"hidden\" name=\"id\" value=\"".$row["ObitID"]."\">";
							echo "<input type=\"submit\" name=\"indivsubmit\"  id=\"indivsubmit\" value=\"".$printableName."\" class=\"button small has-tip left\" data-tooltip aria-haspopup=\"true\" data-disable-hover=\"false\" title=\"Get the whole record\">";
							echo "</form>";
						
						echo "</div>"; // end names
						
						// printing the newspaper title
						echo "<div class=\"small-12 large-expand columns\">";
						if(is_null($row["Newspaper"])) {
							echo "&nbsp;";
						} else {
							echo "<i>".$row["Newspaper"]."</i>";
						}
						echo "</div>";
						
						// printing the date
						$printPub = date("j M Y", strtotime($row["DatePublished"]));
						echo "<div class=\"small-12 large-expand columns\">";
						echo $printPub;
						echo "</div>";
						
						// printing the page and column info
						echo "<div class=\"columns\">";
						if(is_null($row["Page"])) {
							echo "&nbsp; ";
						} else {
							echo "Page ".$row["Page"].", ";
						}
						if(is_null($row["Col"])) {
							echo "&nbsp;";
						} else {
							echo "Column ".$row["Col"];
						}
						echo "</div>";
						
						// printing the church and burial info
						if (($churlen > 0) && ($burlen == 0)) {
							echo "<div class=\"small-12 large-expand columns\">".$row["Church"]."</div>";
						} elseif (($churlen == 0) && ($burlen > 0)) {
							echo "<div class=\"small-12 large-expand columns\">".$row["Burial"]."</div>";
						} elseif (($churlen == 0) && ($burlen > 0)) {
							echo "<div class=\"small-12 large-expand columns\">".$row["Church"]."</div>";
							echo "<div class=\"small-12 large-expand columns\">".$row["Burial"]."</div>";
						} 				
						
						echo "</div>";
					}	// END FOREACH
					echo "</div>";
						
					
					// back links
				if ($page > 1) {
					$firstlink = "<li><a href=\"{$_SERVER['REQUEST_URI']}&page=1\" title=\"First page\"><i class=\"fa fa-angle-double-left fa-lg\"></i></a></li>";
					$backlink = "<li><a href=\"{$_SERVER['REQUEST_URI']}&page=" . ($page - 1) . "\" title=\"Previous page\"><i class=\"fa fa-angle-left fa-lg\"></i></a></li>";
				} else {
					$firstlink = "<li class=\"disabled\"><i class=\"fa fa-angle-double-left fa-lg\"></i></li>";
					$backlink = "<li class=\"disabled\"><i class=\"fa fa-angle-left fa-lg\"></i></li>";
				}
				
				// forward links
				if($page < $pages) {
					$nextlink = "<li><a href=\"{$_SERVER['REQUEST_URI']}&page=" . ($page + 1) . "\" title=\"Next page\"><i class=\"fa fa-angle-right fa-lg\"></i></a></li>";
					$lastlink = "<li><a href=\"{$_SERVER['REQUEST_URI']}&page=" . $pages . "\" title=\"Last page\"><i class=\"fa fa-angle-double-right fa-lg\"></i></a></li>";
				} else {
					$nextlink = "<li class=\"disabled\"><i class=\"fa fa-angle-right fa-lg\"></i></li>";
					$lastlink = "<li class=\"disabled\"><i class=\"fa fa-angle-double-right fa-lg\"></i></li>";
				}
				
				$pagerange = 2;
				
				echo "<ul class=\"pagination text-center\">" . $firstlink . $backlink; 
				for ($z = ($page - $pagerange); $z < (($page + $pagerange)  + 1); $z++) {
					// if it's a valid page number...
					if (($z > 0) && ($z <= $pages)) {
						// if we're on current page...
						if ($z == $page) {
							// 'highlight' it but don't make a link
							echo "<li class=\"current\">".$z."</li>";
						
							// if not current page...
						} else {
							// make it a link
							echo "<li><a href=\"{$_SERVER['REQUEST_URI']}&page=".$z."\" aria-label=\"Page ".$z."\">".$z."</a></li>";
						} // end else
					} // end if 
				} // end for 
				echo $nextlink . $lastlink . "</ul>";
				
			} // end count query
			else {
				echo "<p class=\"callout warning\"><b>0</b> results found. <a href=\"http://jefflibrary.org/obituaries\">Try again with a broader search?</a></p>";
			} 

			$res = null;
			$dbh = null;
		
		} catch (Exception $e) {
			echo '<p>', $e->getMessage(), '</p>';
		} 
		
	}// end if get
	else {
			echo "<p class=\"callout alert\"><b>Error!</b> Apparently the server request isn't working!</p>";
	}
	
?>

		</div>
	</div>
</section>

<?php	
require 'footer.php';	
?>