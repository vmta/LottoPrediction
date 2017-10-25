<?php
function processRelativeFrequency($opt, $draws, $drawmachine, $setofballs) {
    $str = "";
    switch($opt) {
        case "3x":
            $opt1 = " WHERE `guess_3` > 0 ";
            $k = 0.25;
            break;
        case "4x":
            $opt1 = " WHERE `guess_4` > 0 ";
            $k = 0.5;
            break;
        case "5x":
            $opt1 = " WHERE `guess_5` > 0 ";
            $k = 0.75;
            break;
        case "6x":
            $opt1 = " WHERE `guess_6` > 0 ";
            $k = 1.25;
            break;
        default:
            $opt1 = " ";
            $k = 1;
    }
    switch($drawmachine) {
        case "А":
        case "Б":
            $drawmachine1 = " AND `draw_machine` LIKE '".$drawmachine."' ";
            break;
        default:
            $drawmachine1 = "";
    }
    switch($setofballs) {
        case 1:
        case 2:
        case 3:
        case 4:
            $setofballs1 = " AND `set_of_balls` = ".$setofballs." ";
            break;
        default:
            $setofballs1 = "";
    }
    
    $treshold_upper = 10 * $k / $$draws;
    $treshold_lower = 5 * $k / $draws;
    
    $query = "SELECT hot, "
            . "SUM(hits) AS hits "
            . "FROM (";
    for($i = 1; $i < 7; $i++) {
        if(isset($draws)) {
            $draws1 = "(SELECT * FROM `full` ORDER BY `id` DESC LIMIT " . $draws . ") AS table".$i;
        } else {
            $draws1 = "`full`";
        }
        $query .= "SELECT `ball_" . $i . "` AS hot, COUNT(*) AS hits FROM " . $draws1 . $opt1 . $drawmachine1 . $setofballs1 . " GROUP BY hot";
        if($i < 6) {
            $query .= " UNION ALL ";
        }
    }
    $query .= ") AS t1 "
            . "GROUP BY hot "
            . "ORDER BY hits DESC;";
    
    $q_res = mysqli_query($query)
            or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    if(mysqli_num_rows($q_res)) {
        $coords = array();
        $str .= "<span class=\"tableContainer\">"
                . "<table>"
                . "<tr>"
                . "<th colspan=3>" . ((empty($opt)) ? "All" : $opt) .  " in " . ((empty($draws)) ? "all" : $draws) . " games</th>"
                . "</tr>"
                . "<tr>"
                . "<th>" . CONST_HOT . "</th>"
                . "<th>" . CONST_HITS . "</th>"
                . "<th>" . CONST_RATIO . "</th>"
                . "</tr>"
                . "<tr>"
                . "<td colspan=3>" . mysqli_num_rows($q_res) . " совпадений"
                . (($drawmachine == "All" || empty($drawmachine)) ? "" : " по лототрону ".$drawmachine)
                . (($setofballs == "All" || empty($setofballs)) ? "" : " и набору шаров ".$setofballs)
                . ".</td>"
                . "</tr>";
        while($row = mysqli_fetch_array($q_res, MYSQLI_ASSOC)) {
            array_push($coords, (int) $row['ratio']);
            if((int) $row['ratio'] > $treshold_upper) {
                $str .= "<tr class=\"highlight-green\">";
            } elseif((int) $row['ratio'] < $treshold_lower) {
                $str .= "<tr class=\"highlight-red\">";
            } else {
                $str .= "<tr>";
            }
            $str .= "<td class=\"hot\">".$row['hot']."</td>"
                    . "<td class=\"hits\">".$row['hits']."</td>"
                    . "<td class=\"ratio\">".round(100 * ($row['hits'] / (6 * $draws)), 2)."%</td>"
                    . "</tr>";
        }
        $str .= "</table>"
                . "</span>";
    }
    mysqli_free_result($q_res);
    
    return $str;
}
