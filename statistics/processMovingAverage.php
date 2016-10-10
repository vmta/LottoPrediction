<?php
function processMovingAverage($opt, $draws, $drawmachine, $setofballs) {
    
    $str = "";
    
    

    $groupID = 1;
    $groupPeriod = 10;
    $groupSMA = new Average($groupID, $groupPeriod);
    $smaCounter = 0;
    $smaArray = $groupSMA->getSMA();
//    foreach($smaArray as $sma) {
//        $str .= "<br />" . ++$smaCounter . "group 1, 5-draw sma: " . $sma . "<br />";
//    }
    
    $chart = new Chart();
    $str .= $chart->draw($smaArray);
    
    // Draw Charts for 
    
    $groupID = 2;
    $group = new GroupOfNumbers($groupID);
    $str .= "<br />And here comes testing google chart:<br  />";
    $str .= $chart->gDrawWithTrendline($group->get());
    
    return $str;
}