<?php
function processMovingAverage($opt, $draws, $drawmachine, $setofballs) {
    
    $sqlOptions = "";
    switch($opt) {
        case "3x":
            $sqlOptions .= " AND `guess_3` > 0 ";
            break;
        case "4x":
            $sqlOptions .= " AND `guess_4` > 0 ";
            break;
        case "5x":
            $sqlOptions .= " AND `guess_5` > 0 ";
            break;
        case "6x":
            $sqlOptions .= " AND `guess_6` > 0 ";
            break;
        default:
            $sqlOptions .= "";
    }
    switch($drawmachine) {
        case "А":
        case "Б":
            $sqlOptions .= " AND `draw_machine` LIKE '".$drawmachine."' ";
            break;
        default:
            $sqlOptions .= "";
    }
    switch($setofballs) {
        case 1:
        case 2:
        case 3:
        case 4:
            $sqlOptions .= " AND `set_of_balls` = ".$setofballs." ";
            break;
        default:
            $sqlOptions .= "";
    }
    
    $str = "";
    
/**    $groupID = 1;
    $groupPeriod = $draws;
    $groupSMA = new Average($groupID, $sqlOptions, $groupPeriod);
    $smaCounter = 0;
    $smaArray = $groupSMA->getSMA();
    foreach($smaArray as $sma) {
        $str .= "<br />" . ++$smaCounter . "group 1, 5-draw sma: " . $sma . "<br />";
    }
    $chart = new Chart();
    $str .= $chart->draw($smaArray);
*/
    
    // Cycle through all groups (1 to 6)
    // Initialize serie of numbers within the Group
    // as well as serie of averages within the Group
//    $groups = array();
//    for($groupID = 1; $groupID < LOTTERY_BALLS_NUMBER + 1; $groupID++) {
//        
//        $group = array();
//        
//        $groupNumbers = new GroupOfNumbers($groupID, $sqlOptions, $draws);
//        array_push($group, $groupNumbers);
//        
//        $groupAverages = new Average($groupID, $sqlOptions, $draws);
//        array_push($group, $groupAverages);
//        
//        array_push($groups, $group);
//    }
    
    // Cycle through groups array and pass subarrays
    // to a chart processor.
    $chart = new Chart();
//    foreach($groups as $group) {
//        $str .= $chart->gDrawWithTrendline($group[0]->get());
//        $str .= $chart->gDrawWithTrendline($group[1]->get());
//    }
    
    $query = "SELECT ";
    for($i = 1; $i < LOTTERY_BALLS_NUMBER + 1; $i++) {
        $query .= " `ball_" . $i . "` AS `" . $i ."`";
        if($i < LOTTERY_BALLS_NUMBER) {
            $query .= ", ";
        }
    }
    $query .= " FROM `full` WHERE `id` > 917 "
            . $sqlOptions
            . " ORDER BY `id` DESC LIMIT " . $draws;
    $numbersCombined = new LotteryNumbers($query);
    //var_dump($groupsNumsOnly);
    $str .= $chart->gDraw($numbersCombined->get());
    
    return $str;
}