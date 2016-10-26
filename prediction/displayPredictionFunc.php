<?php
function displayPredictionFunc() {
    $str = "";
    
/**    $_array = [
        [2, 5, 8, 19, 45, 51],
        [8, 25, 38, 41, 43, 49],
        [8, 15, 28, 29, 31, 37],
        [1, 15, 27, 29, 39, 52]
    ];
*/
    $cycles = 10;
    $_array = array();
    for($i = 0; $i < $cycles; $i++) {
        array_push($_array, [
            rand(1, 8),
            rand(9, 16),
            rand(17, 24),
            rand(25, 32),
            rand(33, 40),
            rand(41, 52)
        ]);
    }
    
    /**
     * Create array and initialize it with zero values;
     */
    $weight = array();
    for($i = LOTTERY_MIN_NUMBER; $i < LOTTERY_MAX_NUMBER + 1; $i++) {
        $weight[$i]["Number"] = $i;
        $weight[$i]["Weight"] = 0;
        $weight[$i]["Frequency"] = 0;
        $weight[$i]["Latency"] = - count($_array);
    }
    
    $maxRate = count($_array);
    for($draw = 0; $draw < $maxRate; $draw++) {
        $totalBalls = count($_array[$draw]);
        for($ball = 0; $ball < $totalBalls; $ball++) {
            $number = $_array[$draw][$ball];
            $weight[$number]["Number"] = $number;
            $weight[$number]["Weight"] += $maxRate - $draw;
            $weight[$number]["Frequency"] += 1;
            $weight[$number]["Latency"] =  - $weight[$number]["Frequency"];
        }
    }
    $chart = new BarChart(1200, 600);
    $str .= $chart->draw($weight);
    
    
    
    
    $min = 1;
    $max = 10;
    $avg = round(($min + $max) / 2);
    $randomNumber = round(rand($min, $max));
    
    $delta = $avg - $randomNumber;
    $convergencePercentile = ($delta / ($max - $min)) * 100;
    
    $str .= "MIN: " . $min . "<br />";
    $str .= "MAX: " . $max . "<br />";
    $str .= "AVG: " . $avg . "<br />";
    $str .= "Random number: " . $randomNumber . "<br />";
    $str .= "Delta: " . $delta . "<br />";
    $str .= "Convergence: " . $convergencePercentile . "<br />";
    
    
    
    
    
    
    $groupID = 1;
    $min = getMin($_array);
    $str .= "Arrays minimum: " . $min;
    
    return $str;
}

function getMin($array) {
    $localArray = array();
    foreach($array as $value) {
        if(!is_array($value)) {
            array_push($localArray, $value);
        } else {
            getMin($value);
        }
    }
    sort($localArray);
    return $localArray[0];
}