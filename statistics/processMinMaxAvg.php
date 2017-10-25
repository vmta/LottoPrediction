<?php
function processMinMaxAvg($opt, $draws, $drawmachine, $setofballs) {
    
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
    
    // Get an Array of numbers from DB with constructed SQL query
    $validData = new FullData($query);
    
    // Build an HTML using validData
    $str = "<span class=\"tableContainer\">
            <table>
            <tr>
            <th colspan=4>" . ((empty($opt)) ? "All" : $opt) .  " в " . ((empty($draws)) ? "all" : $draws) . " играх</th>
            </tr>
            <tr>
            <th>" . CONST_GROUP . "</th>
            <th>Min</th>
            <th>Max</th>
            <th>Avg</th>
            </tr>
            <tr>
            <td colspan=4>"
            . (($drawmachine == "All" || empty($drawmachine)) ? "" : " по лототрону ".$drawmachine)
            . (($setofballs == "All" || empty($setofballs)) ? "" : " и набору шаров ".$setofballs)
            .".</td>
            </tr>";
    for($groupID = 1; $groupID < LOTTERY_BALLS_NUMBER + 1; $groupID++) {
        $str .= "<tr>
            <td class=\"group\">".$groupID."</td>
            <td class=\"min\">".$validData->getGroupMIN($groupID)."</td>
            <td class=\"max\">".$validData->getGroupMAX($groupID)."</td>
            <td class=\"avg\">".$validData->getGroupAVG($groupID)."</td>
            </tr>";
    }
    $str .= "</table>
            </span>";
    
    return $str;
}