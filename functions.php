<?php

function formatdate($month,$year){
	if ($month == "01" || $month == "03" || $month == "05" || $month == "07" || $month == "08" || $month == 10 || $month == 12) {
			$date = $year."-".$month."-31";
	} elseif ($month == "04" || $month == "06" || $month == "09" || $month == 11) {
		$date = $year."-".$month."-30";
	} elseif ($month == "02") {
		if (checkdate(2, 29, $year)) {
			$date = $year."-".$month."-29";
		} else {
			$date = $year."-".$month."-28";
		}
	}
	return $date;
}

function getmonth($mnum) {
	$month = "";
	if ($mnum == "01") {
		$month = "January";
	} elseif ($mnum == "02") {
		$month = "February";
	} elseif ($mnum == "03") {
		$month = "March";
	} elseif ($mnum == "04") {
		$month = "April";
	} elseif ($mnum == "05") {
		$month = "May";
	} elseif ($mnum == "06") {
		$month = "June";
	} elseif ($mnum == "07") {
		$month = "July";
	} elseif ($mnum == "08") {
		$month = "August";
	} elseif ($mnum == "09") {
		$month = "September";
	} elseif ($mnum == "10") {
		$month = "October";
	} elseif ($mnum == "11") {
		$month = "November";
	} elseif ($mnum == "12") {
		$month = "December";
	}
	return $month;
}

function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
} 

?>