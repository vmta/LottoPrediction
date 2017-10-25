<?php

function displayJackPot($balls, $records) {

    require "db/config.php";
    $dbCon = mysqli_connect("p:".$myHost, $myUser, $myPass, $myDB);

    $str;
    $query = "SELECT * FROM `full` WHERE `guess_" . (empty($balls) ? 6 : $balls) . "` > 0 ORDER BY `id` DESC LIMIT " . (empty($records) ? 100 : $records) . ";";
    $q_res = mysqli_query($dbCon, $query);
//            or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    if(mysqli_num_rows($q_res)) {
//        $coords = array('1'=>array(), '2'=>array(), '3'=>array(), '4'=>array(), '5'=>array(), '6'=>array());
//        $count = 0;
        
        $str = "<span class=\"tableContainer\">"
//                . "<canvas width='600' height='300' id='CanvasJackPot'></canvas>"
                . "<table>"
                . "<tr>"
                . "<th colspan=8>" . (empty($balls) ? JACK_POTS : $balls . "x52") . "</th>"
                . "</tr>"
                . "<tr>"
                . "<th>" . CONST_ID . "</th>"
                . "<th>" . CONST_DATE . "</th>"
                . "<th>I</th>"
                . "<th>II</th>"
                . "<th>III</th>"
                . "<th>IV</th>"
                . "<th>V</th>"
                . "<th>VI</th>"
                . "</tr>";
        while($row = mysqli_fetch_array($q_res, MYSQLI_ASSOC)) {
//            $coords[1][$count] = $row['ball_1'];
//            $coords[2][$count] = $row['ball_2'];
//            $coords[3][$count] = $row['ball_3'];
//            $coords[4][$count] = $row['ball_4'];
//            $coords[5][$count] = $row['ball_5'];
//            $coords[6][$count] = $row['ball_6'];
//            $count++;
            
            $str .= "<tr>"
                    . "<td width=40>".$row['id']."</td>"
                    . "<td width=100>".$row['date']."</td>"
                    . "<td width=30>".$row['ball_1']."</td>"
                    . "<td width=30>".$row['ball_2']."</td>"
                    . "<td width=30>".$row['ball_3']."</td>"
                    . "<td width=30>".$row['ball_4']."</td>"
                    . "<td width=30>".$row['ball_5']."</td>"
                    . "<td width=30>".$row['ball_6']."</td>"
                    . "</tr>";
        }
        $str .= "</table>"
                . "</span>";
        
//        $str .= "<script>"
//                . "var canvas = document.getElementById('CanvasJackPot');"
//                . "var width = 600;"
//                . "var height = 300;";
//        
//        for($j = 1; $j < count($coords) + 1; $j++) {
//            $str .= "var ctx" . $j . " = canvas.getContext('2d');"
//                    . "ctx" . $j . ".moveTo(0, 0);";
//            for($i = 0; $i < count($coords[$j]); $i++) {
//                $str .= "ctx" . $j . ".lineTo(" . $i . "*width/" . count($coords[$j]) . ", height-" . $coords[$j][$i] . "*height/" . count($coords[$j]) . ");";
//            }
//            $str .= "ctx" . $j . ".strokeStyle='rgb(" . rand($j, 255-$i) . ", " . rand($j, 255-$i) . ", " . rand($j, 255-$i) . ")';"
//                    . "ctx" . $j . ".stroke();";
//        }
//        $str .= "</script>";
    }
    mysqli_free_result($q_res);
    return $str;
}
