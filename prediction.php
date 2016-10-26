<?php
$data = "<h2>" . CAT_PREDICTION . "</h2>";

if(isset($_GET['subcat'])) {
    if($_GET['subcat'] == "displayPrediction") {
        include "prediction/displayPrediction.php";
        $data .= "<h3>" . SUBCAT_PREDICTION_AUTO_RANDOM . "</h3>";
        $data .= displayPrediction();
    } elseif($_GET['subcat'] == "displayPredictionFANN") {
        include "prediction/displayPredictionFANN.php";
        $data .= "<h3>" . SUBCAT_PREDICTION_FANN . "</h3>";
        $data .= displayPredictionFANN();
    } elseif($_GET['subcat'] == "displayPredictionFunc") {
        include "prediction/displayPredictionFunc.php";
        $data .= "<h3>" . SUBCAT_PREDICTION_FUNC ."</h3>";
        $data .= displayPredictionFunc();
    }
} else {
    include "prediction/displayPrediction.php";
    $data .= "<h3>" . SUBCAT_PREDICTION_AUTO_RANDOM . "</h3>";
    $data .= displayPrediction();
}

echo $data;