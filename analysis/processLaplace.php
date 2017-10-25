<?php
include "class/combination.analysis.php";
include "class/probability.analysis.php";
include "class/LaplaceConstant.php";

function processLaplace($opt, $draws, $drawmachine, $setofballs, $functionName) {
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
    
    $q_res = mysqli_query($query)
            or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    if(mysqli_num_rows($q_res)) {
        $str = "<span class=\"tableContainer\">"
                . "<table>"
                . "<tr>"
                . "<th colspan=5>" . ((empty($opt)) ? "All" : $opt) .  " в " . ((empty($draws)) ? "all" : $draws) . " играх</th>"
                . "</tr>"
                . "<tr>"
                . "<th>" . CONST_HOT . "</th>"
                . "<th>" . CONST_HITS . " (n)</th>"
                . "<th>" . CONST_RATIO . " (p)</th>"
                . "<th>" . CONST_PROBABILITY . " (P)</th>"
                . "<th>";
        switch($functionName) {
            case "calculateLaplaceLocal":
                $str .= CONST_LAPLACE_LOCAL;
                break;
            case "calculateLaplaceIntegral":
                $str .= CONST_LAPLACE_INTEGRAL;
                break;
            case "calculateLaplaceDeviation":
                $str .= CONST_LAPLACE_DEVIATION;
                break;
        }
        $str .= "</th></tr>"
                . "<tr>"
                . "<td colspan=4>" . mysqli_num_rows($q_res) . " совпадений"
                . (($drawmachine == "All") ? "" : " по лототрону ".$drawmachine)
                . (($setofballs == "All") ? "" : " и набору шаров ".$setofballs)
                . ".</td>"
                . "</tr>";
        $i = 1;
        while($row = mysqli_fetch_array($q_res, MYSQL_ASSOC)) {
            
            $hot = $row['hot'];
            $hits = $row['hits'];
            $ratio = getRate($row['hits'], $draws);
            $bernulliProbability = getProb($row['hits'], $draws);
            
            $str .= "<tr>"
                . "<td class=\"hot\">" . $hot . "</td>"
                . "<td class=\"hits\">". $hits ."</td>"
                . "<td class=\"ratio\">". $ratio ."%</td>"
                . "<td class=\"probability\">" . $bernulliProbability . "%</td>"
                . "<td class=\"probability\">" 
                . getLaplace($hits, 
                    $draws, 
                    $ratio, 
                    $bernulliProbability, 
                    $functionName) 
                . "</td>"
                . "</tr>";
        }
        $str .= "</table>"
                . "</span>";
    }
    mysqli_free_result($q_res);
    return $str;
}

function getRate($m, $n) {
    return round(100 * $m / (6 * $n), 5);
}

function getProb($m, $n) {
    return round(100 * (new Probability($m, $n))->calculateBernulli(), 5);
}

function getLaplace($m, $n, $bernulliProbability, $functionName) {
    
    $str = "";
    
    switch($functionName) {
        case "calculateLaplaceLocal":
            $n *= 6;
            //$p = $bernulliProbability;
            $p = 1 / 52;
            $q = 1 - $p;
            $x = abs(round(($m - $n * $p) / sqrt($n * $p * $q), 5));
            $Fx = (new LaplaceConstant($x))->getFx();
            $str .= (100 * $Fx) . "%";
            break;
        case "calculateLaplaceIntegral":
            
            break;
        case "calculateLaplaceDeviation":
            break;
    }
    return $str;
}
