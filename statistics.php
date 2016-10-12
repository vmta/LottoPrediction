<?php
// SHALL I NEED THIS FUNCTION?
function drawChart($opt, $coords){
	$str = "<canvas style='border:1px solid black;' width='175' height='100' id='Canvas" . $opt . "'></canvas>";
	$str .= "<script>";
	$str .= "var canvas = document.getElementById('Canvas" . $opt . "');";
	$str .= "var ctx = canvas.getContext('2d');";
	$str .= "ctx.moveTo(0, 100);";
	for($i = 0; $i < count($coords) - 1; $i++) {
		$str .= "ctx.lineTo(" . $i . ", " . (100 - $coords[$i]) . ");";
	}
	$str .= "ctx.stroke();";
	$str .= "</script>";
	
	return $str;
}


////////////////////////////
// Process $_GET request. //
////////////////////////////

$data = "<h2>" . CAT_STATISTICS . "</h2>";

if(isset($_GET['subcat'])) {
    if($_GET['subcat'] == "displayEnterNewDraw") {
        include "statistics/displayEnterNewDraw.php";
        $data .= "<h3>" . SUBCAT_STAT_ENTER_NEW_DRAW . "</h3>";
        $data .= displayEnterNewDraw();
    } elseif($_GET['subcat'] == "processEnterNewDraw") {
        include "statistics/displayEnterNewDraw.php";
        include "statistics/processEnterNewDraw.php";
        $data .= "<h3>" . SUBCAT_STAT_ENTER_NEW_DRAW . "</h3>";
        $data .= displayEnterNewDraw();
        $data .= "<br />";
        $data .= processEnterNewDraw();
    } elseif($_GET['subcat'] == "displayMinMaxAvg") {
        include "statistics/displayMinMaxAvg.php";
        include "statistics/processMinMaxAvg.php";
        $data .= "<h3>" . SUBCAT_STAT_MIN_MAX_AVG . "</h3>";
        $data .= displayMinMaxAvg();
        $data .= "<br />";
        $data .= processMinMaxAvg("6x", 50, "", "");
        $data .= processMinMaxAvg("5x", 50, "", "");
        $data .= processMinMaxAvg("4x", 50, "", "");
    } elseif($_GET['subcat'] == "processMinMaxAvg") {
        include "statistics/displayMinMaxAvg.php";
        include "statistics/processMinMaxAvg.php";
        $data .= "<h3>" . SUBCAT_STAT_MIN_MAX_AVG . "</h3>";
        $data .= displayMinMaxAvg();
        $data .= "<br />";
        $data .= processMinMaxAvg($_POST['opt'], $_POST['draws'], $_POST['drawmachine'], $_POST['setofballs']);
    } elseif($_GET['subcat'] == "displayMovingAverage") {
        include "statistics/displayMovingAverage.php";
        $data .= "<h3>" . SUBCAT_STAT_MOVING_AVERAGE . "</h3>";
        $data .= displayMovingAverage();
    } elseif($_GET['subcat'] == "processMovingAverage") {
        include "statistics/displayMovingAverage.php";
        include "statistics/processMovingAverage.php";
        $data .= "<h3>" . SUBCAT_STAT_MOVING_AVERAGE . "</h3>";
        $data .= displayMovingAverage();
        $data .= "<br />";
        $data .= processMovingAverage($_POST['opt'], $_POST['draws'], $_POST['drawmachine'], $_POST['setofballs'], $_POST['aggregator']);
    } elseif($_GET['subcat'] == "displaySeries") {
        include "statistics/displaySeries.php";
        $data .= "<h3>" . SUBCAT_STAT_SERIES . "</h3>";
        $data .= displaySeries();
    } elseif($_GET['subcat'] == "displayRelativeFrequency") {
        include "statistics/displayRelativeFrequency.php";
        include "statistics/processRelativeFrequency.php";
        $data .= "<h3>" . SUBCAT_STAT_RELATIVE_FREQUENCY . "</h3>";
        $data .= displayRelativeFrequency();
        $data .= "<br />";
        $data .= processRelativeFrequency("6x", 50, "", "");
        $data .= processRelativeFrequency("5x", 50, "", "");
        $data .= processRelativeFrequency("4x", 50, "", "");
        $data .= "</span>";
    } elseif($_GET['subcat'] == "processRelativeFrequency") {
        include "statistics/displayRelativeFrequency.php";
        include "statistics/processRelativeFrequency.php";
        $data .= "<h3>" . SUBCAT_STAT_RELATIVE_FREQUENCY . "</h3>";
        $data .= displayRelativeFrequency();
        $data .= "<br />";
        $data .= processRelativeFrequency($_POST['opt'], $_POST['draws'], $_POST['drawmachine'], $_POST['setofballs']);
    }
} else {
    include "home/displayLastGames.php";
    $data .= displayLastGames(20);
}

echo $data;