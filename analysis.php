<?php
$data = "<h2>" . CAT_ANALYSIS . "</h2>";

if(isset($_GET['subcat'])) {
    if($_GET['subcat'] == "displayCheckTicket") {
        include "analysis/displayCheckTicket.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_CHECKTICKET . "</h3>";
        $data .= displayCheckTicket();
    } elseif($_GET['subcat'] == "processCheckTicket") {
        include "analysis/displayCheckTicket.php";
        include "analysis/processCheckTicket.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_CHECKTICKET . "</h3>";
        $data .= displayCheckTicket($_POST['drawDate'],
                $_POST['drawID'],
                $_POST['drawNums'],
                $_POST['iterationDepth']);
        $data .= "<br />";
        $data .= processCheckTicket($_POST['drawDate'],
                $_POST['drawID'],
                $_POST['drawNums'],
                $_POST['iterationDepth']);
    } elseif($_GET['subcat'] == "displayProbability") {
        include "analysis/displayProbability.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_PROBABILITY . "</h3>";
        $data .= displayProbability();
    } elseif($_GET['subcat'] == "displayBernulli") {
        include "analysis/displayBernulli.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_BERNULLI . "</h3>";
        $data .= displayBernulli();
    } elseif($_GET['subcat'] == "processBernulli") {
        include "analysis/displayBernulli.php";
        include "analysis/processBernulli.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_BERNULLI . "</h3>";
        $data .= displayBernulli();
        $data .= "<br />";
        $data .= processBernulli($_POST['opt'], 
                $_POST['draws'], 
                $_POST['drawmachine'], 
                $_POST['setofballs']);
    } elseif($_GET['subcat'] == "displayLaplace") {
        include "analysis/displayLaplace.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_LAPLACE . "</h3>";
        $data .= displayLaplace();
    }  elseif($_GET['subcat'] == "processLaplace") {
        include "analysis/displayLaplace.php";
        include "analysis/processLaplace.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_LAPLACE . "</h3>";
        $data .= displayLaplace();
        $data .= "<br />";
        $data .= processLaplace($_POST['opt'], 
                $_POST['draws'], 
                $_POST['drawmachine'], 
                $_POST['setofballs'], 
                $_POST['functionName']);
    } elseif($_GET['subcat'] == "displayCorrelationCoefficient") {
        include "analysis/displayCorrelationCoefficient.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_CORRELATION_COEFFICIENT . "</h3>";
        $data .= displayCorrelationCoefficient();
    } elseif($_GET['subcat'] == "processCorrelationCoefficient") {
        include "analysis/displayCorrelationCoefficient.php";
        include "analysis/processCorrelationCoefficient.php";
        $data .= "<h3>" . SUBCAT_ANALYSIS_CORRELATION_COEFFICIENT . "</h3>";
        $data .= displayCorrelationCoefficient();
        $data .= "<br />";
        $data .= processCorrelationCoefficient($_POST['pair'],
                $_POST['opt'], 
                $_POST['draws'], 
                $_POST['drawmachine'], 
                $_POST['setofballs']);
    }
} else {
    include "analysis/displayProbability.php";
    $data .= "<h3>" . SUBCAT_ANALYSIS_PROBABILITY . "</h3>";
    $data .= displayProbability();
}

echo $data;