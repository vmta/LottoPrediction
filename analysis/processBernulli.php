<?php
include "class/combination.analysis.php";
include "class/probability.analysis.php";

function processBernulli($opt, $draws, $drawmachine, $setofballs) {

    require "db/config.php";
    $dbCon = mysqli_connect("p:".$myHost, $myUser, $myPass, $myDB);
    mysqli_set_charset($dbCon, 'utf8');
    
    switch($opt) {
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
    
    $query = "SELECT hot, SUM(hits) AS hits "
            . "FROM (";
    for($i = 1; $i < 7; $i++) {
        if(isset($draws)) {
            $draws1 = "(SELECT * FROM `full` ORDER BY `id` DESC LIMIT " . $draws . ") AS table".$i;
        } else {
            $draws1 = "`full`";
        }
        $query .= "SELECT `ball_" . $i . "` AS hot, COUNT(*) AS hits FROM " . $draws1 . $opt1 . $drawmachine1 . $setofballs1 . " group by hot";
        if($i < 6) {
            $query .= " UNION ALL ";
        }
    }
    $query .= ") AS t1 GROUP BY hot ORDER BY hot ASC;";
    
    $q_res = mysqli_query($dbCon, $query);
    if(mysqli_num_rows($q_res)) {
        $str = "<span class=\"tableContainer\">"
                . "<table>"
                . "<tr>"
                . "<th colspan=4>" . ((empty($opt)) ? "All" : $opt) .  " в " . ((empty($draws)) ? "all" : $draws) . " играх</th>"
                . "</tr>"
                . "<tr>"
                . "<th>" . CONST_HOT . "</th>"
                . "<th>" . CONST_HITS . " (n)</th>"
                . "<th>" . CONST_RATIO . " (p)</th>"
                . "<th>" . CONST_PROBABILITY . " (P)</th>"
                . "</tr>"
                . "<tr>"
                . "<td colspan=4>" . mysqli_num_rows($q_res) . " совпадений"
                . (($drawmachine == "All") ? "" : " по лототрону ".$drawmachine)
                . (($setofballs == "All") ? "" : " и набору шаров ".$setofballs)
                . ".</td>"
                . "</tr>";
        $i = 1;
        while($row = mysqli_fetch_array($q_res, MYSQLI_ASSOC)) {
            $str .= "<tr>"
                . "<td class=\"hot\">".$row['hot']."</td>"
                . "<td class=\"hits\">".$row['hits']."</td>"
                . "<td class=\"ratio\">". getRate($row['hits'], $draws) ."</td>"
                . "<td class=\"probability\">" . getProb($row['hits'], $draws) . "</td>"
                . "</tr>";
        }
        $str .= "</table>"
                . "</span>";
    }
    mysqli_free_result($q_res);
    return $str;
}

function getRate($hits, $draws) {
    return round(100 * $hits / (6 * $draws), 5) . "%";
}

function getProb($m, $n) {
    return round(100 * (new Probability($m, $n))->calculateBernulli(), 5) . "%";
}
