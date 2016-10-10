<?php
function getSubnav($cat) {
    if($cat === "statistics") {
        $str = "<ul class=\"subnav\">
            <li><a href=\"?cat=" . $cat . "&subcat=displayEnterNewDraw\">" . SUBCAT_STAT_ENTER_NEW_DRAW . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayMinMaxAvg\">" . SUBCAT_STAT_MIN_MAX_AVG . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displaySeries\">" . SUBCAT_STAT_SERIES . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayRelativeFrequency\">" . SUBCAT_STAT_RELATIVE_FREQUENCY . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayMovingAverage\">" . SUBCAT_STAT_MOVING_AVERAGE . "</a></li>
            </ul>";
    } elseif($cat === "analysis") {
        $str = "<ul class=\"subnav\">
            <li><a href=\"?cat=" . $cat . "&subcat=displayCheckTicket\">" . SUBCAT_ANALYSIS_CHECKTICKET . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayProbability\">" . SUBCAT_ANALYSIS_PROBABILITY . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayBernulli\">" . SUBCAT_ANALYSIS_BERNULLI . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayLaplace\">" . SUBCAT_ANALYSIS_LAPLACE . "</a></li>
            <li><a href=\"?cat=" . $cat . "&subcat=displayCorrelationCoefficient\">" . SUBCAT_ANALYSIS_CORRELATION_COEFFICIENT . "</a></li>
            </ul>";
    } elseif($cat === "prediction") {
        $str = "<ul class=\"subnav\">
            <li><a href=\"?cat=" . $cat . "&subcat=BBB\">BBB</a></li>
            </ul>";
    }
    return $str;
}