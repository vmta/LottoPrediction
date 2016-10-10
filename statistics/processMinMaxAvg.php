<?php
function processMinMaxAvg($opt, $draws, $drawmachine, $setofballs) {
/**    switch($opt) {
        case "3x":
            $opt1 = " WHERE `guess_3` > 0 ";
            break;
        case "4x":
            $opt1 = " WHERE `guess_4` > 0 ";
            break;
        case "5x":
            $opt1 = " WHERE `guess_5` > 0 ";
            break;
        case "6x":
            $opt1 = " WHERE `guess_6` > 0 ";
            break;
        default:
            $opt1 = "";
    }
 * 
 */
 /**   switch($drawmachine) {
        case "А":
        case "Б":
            $drawmachine1 = " AND `draw_machine` LIKE '".$drawmachine."' ";
            break;
        default:
            $drawmachine1 = "";
    }
  * 
  */
/**    switch($setofballs) {
        case 1:
        case 2:
        case 3:
        case 4:
            $setofballs1 = " AND `set_of_balls` = ".$setofballs." ";
            break;
        default:
            $setofballs1 = "";
    }
 * 
 */
    
/**    for($i = 1; $i < 7; $i++) {
        $query .= "SELECT "
                . "MAX(`ball_" . $i . "`) AS maximum, "
                . "MIN(`ball_" . $i . "`) AS minimum, "
                . "AVG(`ball_" . $i . "`) AS average "
                . "FROM `full`" . $opt1 . $drawmachine1 . $setofballs1;
        if($i < 6) {
            $query .= "UNION ALL ";
        }
    }
 * 
 */
    
/**    $q_res = mysql_query($query)
            or die("Could not perform ".$query."<br />".mysql_error()."<br />");
 * 
 */
/**    if(mysql_num_rows($q_res)) {
        $str = "<span class=\"tableContainer\">"
                . "<table>"
                . "<tr>"
                . "<th colspan=4>" . ((empty($opt)) ? "All" : $opt) .  " в " . ((empty($draws)) ? "all" : $draws) . " играх</th>"
                . "</tr>"
                . "<tr>"
                . "<th>" . CONST_GROUP . "</th>"
                . "<th>Min</th>"
                . "<th>Max</th>"
                . "<th>Avg</th>"
                . "</tr>"
                . "<tr>"
                . "<td colspan=4>" . mysql_num_rows($q_res) . " совпадений"
                . (($drawmachine == "All" || empty($drawmachine)) ? "" : " по лототрону ".$drawmachine)
                . (($setofballs == "All" || empty($setofballs)) ? "" : " и набору шаров ".$setofballs)
                . ".</td>"
                . "</tr>";
        $i = 1;
        while($row = mysql_fetch_array($q_res, MYSQL_ASSOC)) {
            $str .= "<tr>"
                . "<td class=\"group\">".$i++."</td>"
                . "<td class=\"min\">".$row['minimum']."</td>"
                . "<td class=\"max\">".$row['maximum']."</td>"
                . "<td class=\"avg\">".round($row['average'])."</td>"
                . "</tr>";
        }
        $str .= "</table>"
                . "</span>";
    }
    mysql_free_result($q_res);
 * 
 */    
    
    
    // Using Class GroupOfNumbers
    $groups = array();

    // Construct sqlOptions
    $sqlOptions = "";
    if(isset($opt) && !empty($opt) && $opt != "All") {
        $sqlOptions .= " AND `guess_" . substr($opt, 0, 1) . "` > 0 ";
    }
    if(isset($drawmachine) && !empty($drawmachine) && $drawmachine != "All") {
        $sqlOptions .= " AND `draw_machine` LIKE '".$drawmachine."' ";
    }
    if(isset($setofballs) && !empty($setofballs) && $setofballs != "All") {
        $sqlOptions .= " AND `set_of_balls` = ".$setofballs." ";
    }

    // Cycle through groups
    for($groupID = 1; $groupID <= LOTTERY_BALLS_NUMBER; $groupID++) {
        array_push($groups, new GroupOfNumbers($groupID, $sqlOptions));
    }
    
    // Build an HTML
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
    for($groupID = 0; $groupID < LOTTERY_BALLS_NUMBER; $groupID++) {
        $str .= "<tr>
            <td class=\"group\">".($groupID + 1)."</td>
            <td class=\"min\">".$groups[$groupID]->getMIN()."</td>
            <td class=\"max\">".$groups[$groupID]->getMAX()."</td>
            <td class=\"avg\">".round($groups[$groupID]->getAVG())."</td>
            </tr>";
    }
    $str .= "</table>
            </span>";

    return $str;
}