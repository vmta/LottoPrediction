<?php
include "class/Correlation.php";

function processCorrelationCoefficient($pair, $opt, $draws, $drawmachine, $setofballs) {

    require "db/config.php";
    $dbCon = mysqli_connect("p:".$myHost, $myUser, $myPass, $myDB);
    mysqli_set_charset($dbCon, 'utf8');
    
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
    if(mysqli_num_rows($q_res)) {
        while($row = mysqli_fetch_array($q_res, MYSQL_ASSOC)) {
            array_push($arrayFull, $row);
        }
    }
    
    $cVal1 = "";
    $cVal2 = "";
    for($i = 1; $i < 6; $i++) {
        $pVal = $i . "-" . ($i + 1);
        $R = new Correlation($arrayFull, $pVal);
        $cVal1 .= "Пара " . $pVal . ": " . $R->getPearson();
        $cVal2 .= "Пара " . $pVal . ": " . $R->getSpearman();
/**        $cVal1 .= "Пара " . $pVal . ": " . getPearson($arrayFull, $pVal);
        $cVal2 .= "Пара " . $pVal . ": " . getSpearman($arrayFull, $pVal);
*/        if ($i < 5) {
            $cVal1 .= "<br />";
            $cVal2 .= "<br />";
        }
    }
    $str .= "<script>
            document.getElementById('dataRow').innerHTML = '<td>". $cVal1 ."</td><td>". $cVal2 ."</td>';
            document.getElementById('dataRow').style.visibility = 'visible';
            var tdElements = document.getElementById('dataRow').getElementsByTagName('td');
            for(var i = 0; i < tdElements.length; i++) {
                tdElements[i].style.paddingLeft = '10%';
                tdElements[i].style.paddingRight = '10%';
                tdElements[i].style.borderBottom = '1px solid #ddeedd';
            }
            </script>";
    
    mysqli_free_result($q_res);
    return $str;
}

/**
function getX($pair) {
    return 'ball_' . substr($pair, 0, 1);
}
function getY($pair) {
    return 'ball_' . substr($pair, -1, 1);
}

function getPearson($array, $pair) {
    $Rxy = 0;
    
    $mX = 0;
    $mX2 = 0;
    $mY = 0;
    $mY2 = 0;
    $mXY = 0;
    $N = 0;
    
    foreach($array as $arr) {
        
        $x = $arr[getX($pair)];
        $y = $arr[getY($pair)];
        
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

function getSpearman($array, $pair) {
    
    $xsorted = array();
    $ysorted = array();
    $xties = (object) array();
    $yties = (object) array();
    
    foreach($array as $arr) {
        // Setting first serie
        $x = $arr[getX($pair)];
        array_push($xsorted, $x);
        if(!isset($xties->{$x}))
            $xties->{$x} = new RankedPoint($x);
        else
            $xties->{$x}->count += 1;
        // Setting second serie
        $y = $arr[getY($pair)];
        array_push($ysorted, $y);
        if(!isset($yties->{$y}))
            $yties->{$y} = new RankedPoint($y);
        else
            $yties->{$y}->count += 1;
    }
    // Reverse sorting
    rsort($xsorted);
    rsort($ysorted);
    
    for($i = 0; $i < count($xsorted); $i++) {
        if($xties->{$xsorted[$i]}->rank == 0)
            $xties->{$xsorted[$i]}->rank = (($i + 1) + ($i + 1) + $xties->{$xsorted[$i]}->count - 1) / 2;
        if($yties->{$ysorted[$i]}->rank == 0)
            $yties->{$ysorted[$i]}->rank = (($i + 1) + ($i + 1) + $yties->{$ysorted[$i]}->count - 1) / 2;
    }
    
    $resArray = array();
    for($i = 0; $i < count($xsorted); $i++) {
        $tmpArray = array("ball_1" => $xties->{$xsorted[$i]}->rank, "ball_2" => $yties->{$ysorted[$i]}->rank);
        array_push($resArray, $tmpArray);
    }
    
    return getPearson($resArray, "1-2");
}

class Point {
    public $ball_1;
    public $ball_2;
    public function __construct($x, $y) {
        $this->ball_1 = $x;
        $this->ball_2 = $y;
    }
}

class RankedPoint {
    public $value;
    public $count;
    public $rank;
    public function __construct($value) {
        $this->value = $value;
        $this->count = 1;
        $this->rank = 0;
    }
}*/
