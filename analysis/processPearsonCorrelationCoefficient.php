<?php
function processPearsonCorrelationCoefficient($pair, $opt, $draws, $drawmachine, $setofballs) {
    
    $arrayFull = array();
    
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
    
    $query = "SELECT 
            `ball_1`,
            `ball_2`,
            `ball_3`,
            `ball_4`,
            `ball_5`,
            `ball_6`
            FROM `full`"
            . (!empty($opt) ? $opt1 : "")
            . (!empty($drawmachine) ? $drawmachine1 : "")
            . (!empty($setofballs) ? $setofballs1 : "")
            . " ORDER BY `id` DESC"
            . (!empty($draws) ? " LIMIT " . $draws : "");
    
    $q_res = mysqli_query($dbCon, $query);
            //or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    if(mysqli_num_rows($q_res)) {
        while($row = mysqli_fetch_array($q_res, MYSQLI_ASSOC)) {
            array_push($arrayFull, $row);
        }
    }
    
    $str = "<p>Для пары <b>" . $pair . "</b> коэффициент корреляции составляет: <b>"
            . getCoefficient($arrayFull, $pair) . "</b></p>";
    
    $str .= "<p>Для пары 1-2 коэффициент корреляции составляет: "
            . getCoefficient($arrayFull, "1-2") . "</p>";
    $str .= "<p>Для пары 2-3 коэффициент корреляции составляет: "
            . getCoefficient($arrayFull, "2-3") . "</p>";
    $str .= "<p>Для пары 3-4 коэффициент корреляции составляет: "
            . getCoefficient($arrayFull, "3-4") . "</p>";
    $str .= "<p>Для пары 4-5 коэффициент корреляции составляет: "
            . getCoefficient($arrayFull, "4-5") . "</p>";
    $str .= "<p>Для пары 5-6 коэффициент корреляции составляет: "
            . getCoefficient($arrayFull, "5-6") . "</p>";
    
    mysqli_free_result($q_res);
    return $str;
}

function getCoefficient($array, $pair) {
    
    $Rxy = 0;
    
    $mX = 0;
    $mX2 = 0;
    $mY = 0;
    $mY2 = 0;
    $mXY = 0;
    $N = 0;
    
    foreach($array as $arr) {
        
        $x = $arr['ball_' . substr($pair, 0, 1)];
        $y = $arr['ball_' . substr($pair, -1, 1)];
        
        $mX += $x;
        $mX2 += $x * $x;
        $mY += $y;
        $mY2 += $y * $y;
        $mXY += $x * $y;
        $N++;
        
    }
    
    $mX /= $N;
    $mX2 /= $N;
    $mY /= $N;
    $mY2 /= $N;
    $mXY /= $N;
    
    $Rxy = ($mXY - $mX * $mY) / (sqrt($mX2 - $mX * $mX) * sqrt($mY2 - $mY * $mY));
    
    return $Rxy;
    
}
