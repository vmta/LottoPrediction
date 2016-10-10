<?php
$data = "<h2>" . CAT_PREDICTION . "</h2>";

if(isset($_GET['subcat'])) {
    if($_GET['subcat'] == "displayPrediction") {
        include "prediction/displayPrediction.php";
        $data .= "<h3>" . SUBCAT_PREDICTION_AUTO_RANDOM . "</h3>";
        $data .= displayPrediction();
    }
} else {
    include "prediction/displayPrediction.php";
    $data .= "<h3>" . SUBCAT_PREDICTION_AUTO_RANDOM . "</h3>";
    $data .= displayPrediction();
}

echo $data;