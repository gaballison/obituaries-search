<?php
require 'header.php'; 
require 'nav.php';
require 'functions.php';
?>

<section>
	<div class="row" style="padding-top: 1rem;">
		&nbsp; <a class="button primary" id="back" onclick="goBack()"><i class="fa fa-arrow-left"></i> &nbsp; Back to search results</a>
	</div>

<?php
	
	if($_SERVER["REQUEST_METHOD"] == "GET") {
		$obitid = test_input($_GET["id"]);
		
		if (!preg_match("/^[0-9]*$/",$obitid)){ 
			echo "<p class=\"callout alert\">Obituary ID must be a number. How did you manage that? <a href=\"https://jefflibrary.org/obituaries/index.php\">Go back and try again</a>.</p>";
		} 
		else {
			// Create connection
			require 'config.php';
			$dsn = 'mysql:host=' . $servername . ';dbname=' . $dbname;
			$data = new PDO($dsn, $user, $pass);		
			$sql = "SELECT COUNT(*) FROM Obituaries WHERE ObitID = '".$obitid."' ";
			
			// if the connection works?
			if ($res = $data->query($sql)) {
				$rescount = $res->fetchColumn();
					
				// if there is a result...
				if ($rescount > 0) {
					$sequel = "SELECT * FROM Obituaries WHERE ObitID = '".$obitid."' ";
					$state = $data->query($sequel);	
									
					//display the results
					foreach ($data->query($sequel) as $row) {
						if(is_null($row["LastName"])) {
							$printLN = " ";
						} else {
							$printLN = $row["LastName"];
						}
						if(is_null($row["FirstMiddleName"])) {
							$printFN = " ";
						} else {
							$printFN = $row["FirstMiddleName"];
						}
						echo "<div class=\"row\"> &nbsp; <h2>Obituary for ".$printFN." ".$printLN."</h2></div>";
						echo "<div class=\"row\" style=\"padding-top: 1rem;\">";
						
						$catLabel = "<div class=\"row\"><div class=\"small-4 large-3 columns resultHead\">";
						$catValue = "</div><div class=\"small-8 large-9 columns resultMain\">";
						$catEnd = "</div></div>";
					
						// person information
						echo "<div class=\"small-12 medium-8 columns\">";
						echo $catLabel . "Obituary ID: ". $catValue . $row["ObitID"] . $catEnd;
						echo $catLabel . "Full Name: " . $catValue . $printFN." ".$printLN . $catEnd;
						if(!is_null($row["MaidenName"])) {
							echo $catLabel."Maiden Name: ".$catValue;
							echo $row["MaidenName"].$catEnd;
						}
						if(!is_null($row["OtherNames"])) {
							echo $catLabel."Other Names: ".$catValue;
							echo $row["OtherNames"].$catEnd;
						} 
						if(!is_null($row["Age"])) {
							echo $catLabel."Age: ".$catValue;
							echo $row["Age"].$catEnd;
						}
						if(!is_null($row["Military"])) {
							echo $catLabel."Military: ".$catValue;
							echo $row["Military"].$catEnd;
						}
						if(!is_null($row["DeathDate"])) {
							echo $catLabel."Date of Death: ".$catValue;
							$printDeath = date("j F Y", strtotime($row["DeathDate"]));
							echo $printDeath.$catEnd;
						}
						if(!is_null($row["DeathPlace"])) {
							echo $catLabel."Place of Death: ".$catValue;
							echo $row["DeathPlace"].$catEnd;
						}
						if(!is_null($row["Hometown"])) {
							echo $catLabel."Hometown: ".$catValue;
							echo $row["Hometown"].$catEnd;
						}
						if(!is_null($row["FuneralHome"])) {
							echo $catLabel."Funeral Home: ".$catValue;
							echo $row["FuneralHome"].$catEnd;
						}
						if(!is_null($row["Church"])) {
							echo $catLabel."Church: ".$catValue;
							echo $row["Church"].$catEnd;
						}
						if(!is_null($row["Burial"])) {
							echo $catLabel."Burial: ".$catValue;
							echo $row["Burial"].$catEnd;
						}
						if(!is_null($row["Notes"])) {
							echo $catLabel."Notes: ".$catValue;
							echo $row["Notes"].$catEnd;
						}
						echo "</table></div>";
						
						// newspaper information
						echo "<div class=\"small-12 medium-4 columns\"><div class=\"callout secondary\"><h4 class=\"news\">Newspaper Information</h4>";
						$catLabel2 = "<div class=\"row\"><div class=\"small-4 columns resultHead\">";
						$catValue2 = "</div><div class=\"small-8 columns resultMain\">";
						$catEnd2 = "</div></div>";
						
						if(!is_null($row["Newspaper"])) {
							echo $catLabel2."Newspaper: ".$catValue2;
							echo "<i>".$row["Newspaper"]."</i>".$catEnd2;
						}
						if(!is_null($row["DatePublished"])) {
							$printPub2 = date("j F Y", strtotime($row["DatePublished"]));
							if(!is_null($row["Day"])) {
								$printDay = $row["Day"];
								echo $catLabel2."Published: ".$catValue2;
								echo $row["Day"].", ".$printPub2.$catEnd2;
							} else {
								echo $catLabel2."Published: ".$catValue2;
								echo $printPub2.$catEnd2;
							}
						}
						if(!is_null($row["Section"])) {
							$printSec = $row["Section"];
							echo $catLabel2."Section: ".$catValue2;
							echo $row["Section"].$catEnd2;
						}
						if((!is_null($row["Col"])) && (!is_null($row["Page"]))) {
							$printPage = $row["Page"];
							$printCol = $row["Col"];
							echo $catLabel2."Location: ".$catValue2;
							echo "Page ".$row["Page"].", Column ".$row["Col"].$catEnd2;
						} elseif ((is_null($row["Col"])) && (!is_null($row["Page"])))  {
							$printPage = $row["Page"];
							echo $catLabel2."Page: ".$catValue2;
							echo $row["Page"].$catEnd2;
						} elseif ((is_null($row["Page"])) && (!is_null($row["Col"]))) {
							$printCol = $row["Col"];
							echo $catLabel2."Column: ".$catValue2;
							echo $row["Col"].$catEnd2;
						}
						echo "</div>"; // end newspaper div

						echo "</div>"; // end right column
					} // end FOREACH
				} else {
					echo "<div class=\"callout warning\">There is no obituary with an ID of <b>".$obitid."</b>. <a href=\"http://jefflibrary.org/obituaries/index.php\">Try another search</a>.</div>";
				} 
			}
			$res = null;
			$data = null;
		} // end ELSE
	} // end if get
	else {
			echo "<p class=\"callout alert\"><b>Error!</b> Apparently the server request isn't working!</p>";
	}
	
?>
</section>

<?php	
require 'footer.php';	
?>