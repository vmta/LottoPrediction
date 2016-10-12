<?php
function processMovingAverage($opt, $draws, $drawmachine, $setofballs, $aggregator) {
    
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
    
    // Construct an SQL query
    $query = "SELECT ";
    for($i = 1; $i < LOTTERY_BALLS_NUMBER + 1; $i++) {
//        $query .= " `ball_" . $i . "` AS `" . $i ."`";
        $query .= " `ball_" . $i . "`";
        if($i < LOTTERY_BALLS_NUMBER) {
            $query .= ", ";
        }
    }
    $query .= " FROM `full` WHERE `id` > 917 "
            . $sqlOptions
            . " ORDER BY `id` DESC LIMIT " . $draws;
    
    $str = "";
    $chart = new Chart();
    
    // Get an Array of numbers from DB with constructed SQL query
    $validData = new FullData($query);
    
    // Display combined graphic for all groups
//    $str .= $chart->gDraw($validData->get());
    
    // Display numbers/SMA/WMA per group
    $groupID = 1;
    $group = $validData->getGroup($groupID);
    $str .= $chart->gDrawGROUP($group, $groupID);
    var_dump($group);
    
    print "<br /><br />";
    
    $sma = $validData->getGroupSMA($groupID, $aggregator);
    $str .= $chart->gDrawSMA($sma, $groupID, $aggregator);
    var_dump($sma);
    
    print "<br /><br />";
    
    $wma = $validData->getGroupWMA($groupID, $aggregator);
    $str .= $chart->gDrawWMA($wma, $groupID, $aggregator);
    var_dump($wma);

    
    
    return $str;
}