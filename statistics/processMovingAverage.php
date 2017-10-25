<?php
function processMovingAverage($opt, $draws, $drawmachine, $setofballs, $aggregator, $groupID) {
    
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
    $query = "SELECT `id`, ";
    for($i = 1; $i < LOTTERY_BALLS_NUMBER + 1; $i++) {
        $query .= " `ball_" . $i . "`";
        if($i < LOTTERY_BALLS_NUMBER) {
            $query .= ", ";
        }
    }
    $query .= " FROM `full` WHERE `id` > 917 "
            . $sqlOptions
            . " ORDER BY `id` DESC LIMIT " . $draws;
    
    $str = "";
    
    // Get an Array of numbers from DB with constructed SQL query
    $validData = new FullData($query);
    
    // Need to join three data sources: group numbers, group sma and group wma
    // then pass this new array to chart drawer.
    $gIds = $validData->getIDs();
    
    $str .= "<table>";
    for($groupID = 1; $groupID < LOTTERY_BALLS_NUMBER + 1; $groupID++) {
        $str .= "<tr>";
        $str .= "<td>";
            $gNums = $validData->getGroup($groupID);
            $gSma = $validData->getGroupSMA($groupID, $aggregator);
            $gWma = $validData->getGroupWMA($groupID, $aggregator);
            $combined = array();
            for($i = 0; $i < count($gNums); $i++) {
                array_push($combined, 
                        [
                            "ID" => $gIds[$i],
                            "Group".$groupID => $gNums[$i],
                            "SMA" => $gSma[$i]['SMA'],
                            "WMA" => $gWma[$i]['WMA']
                        ]);
            }
            $chart = new LineChart(500, 300);
            $str .= $chart->draw($combined);
        $str .= "</td>";
        $groupID++;
        $str .= "<td>";
            $gNums = $validData->getGroup($groupID);
            $gSma = $validData->getGroupSMA($groupID, $aggregator);
            $gWma = $validData->getGroupWMA($groupID, $aggregator);
            $combined = array();
            for($i = 0; $i < count($gNums); $i++) {
                array_push($combined, 
                        [
                            "ID" => $gIds[$i],
                            "Group".$groupID => $gNums[$i],
                            "SMA" => $gSma[$i]['SMA'],
                            "WMA" => $gWma[$i]['WMA']
                        ]);
            }
            $chart = new LineChart(500, 300);
            $str .= $chart->draw($combined);
        $str .= "</td>";
        $str .= "</tr>";
    }
    $str .= "</table>";
    
    return $str;
}