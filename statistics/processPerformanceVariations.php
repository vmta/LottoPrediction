<?php
function processPerformanceVariations(
        $draws,
        $drawmachine,
        $setofballs,
        $aggregator,
        $groupID,
        $algorithm,
        $forecastFrame) {
    
    $sqlOptions = "";
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
    $query = "SELECT `id`, `date`, ";
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
    
    // Check if a single or all groups of numbers shall be processed.
    if($groupID == "All") {
        // If all groups to be displayed, then process them in a loop.
        for($gID = 1; $gID <= LOTTERY_BALLS_NUMBER; $gID++) {
            $str .= prepareData($validData,
                    $gID,
                    $aggregator,
                    $algorithm,
                    $forecastFrame);
        }
    } else {
        // Otherwise only display processed information for a specific group.
        $str .= prepareData($validData,
                $groupID,
                $aggregator,
                $algorithm,
                $forecastFrame);
    }
    
    return $str;
}

/**
 * Function prepares statistical and graphical data view for specific group.
 * 
 * @param array $validData
 * @param int $groupID
 * @return string
 */
function prepareData($validData,
        $groupID,
        $aggregator,
        $algorithm,
        $forecastFrame) {
    
    /**
     * Define MINIMUM, AVERAGE and MAXIMUM values for the group.
     */
    $min = $validData->getGroupMIN($groupID);
    $max = $validData->getGroupMAX($groupID);
    $avg = $validData->getGroupAVG($groupID);
    
    /**
     * Need to join data sources:
     * group numbers,
     * group sma,
     * prediction then pass this new array to chart drawer.
     */
    $gIds = $validData->getIDs();
    $gNums = $validData->getGroup($groupID);
    $gSMA = $validData->getGroupSMA($groupID, $aggregator);
    $gWMA = $validData->getGroupWMA($groupID, $aggregator);
    $trend = new Trend($validData, $groupID, $forecastFrame);
    $gTrend = $trend->getData($algorithm);
    
    $sumdev = 0;
    $sumvarp = 0;
    $counter = count($gNums);
    for($id = 0; $id < $counter; $id++) {
        $sumdev += abs($gNums[$id] - $avg);
        $sumvarp += pow(($gNums[$id] - $avg), 2);
    }
    // Average Linear Deviation
    $avgdev = $sumdev / $counter;
    // Variance (both general and local)
    $varp = $sumvarp / $counter;
    $var_ = $sumvarp / ($counter - 1);
    // Standard Deviation (both general and local)
    $stdevp = sqrt($varp);
    $stdev_ = sqrt($var_);
    // Coefficient of variation
    $k_var = $stdev_ / $avg;
    // Oscillation rate
    $k_osc = (($max - $min) / $avg);
    
    $chart = new LineChart(600, 300);
    $data = array();
    for($i = 0; $i < count($gNums); $i++) {
        array_push($data,
                [
                    "ID" => $gIds[$i],
                    "Group".$groupID => $gNums[$i],
                    "SMA" => $gSMA[$i]['SMA'],
                    "WMA" => $gWMA[$i]['WMA'],
                    "Trend" => $gTrend[$i]
                ]);
    }
    
    $forecast = new Forecast($trend);
    
    $_str = "
            <table>
            <tr>
                <td>Минимум</td>
                <td>" . $min . "</td>
                <td rowspan=\"11\">" . $chart->draw($data) . "</td>
            </tr><tr>
                <td>Максимум</td>
                <td>" . $max . "</td>
            </tr><tr>
                <td>Среднее арифметическое</td>
                <td>" . $avg . "</td>
            </tr><tr>
                <td>Размах вариации</td>
                <td>" . ($max - $min) . "</td>
            </tr><tr>
                <td>Среднее линейное отклонение</td>
                <td>" . $avgdev . "</td>
            </tr><tr>
                <td>Дисперсия по генеральной совокупности</td>
                <td>" . $varp . "</td>
            </tr><tr>
                <td>Дисперсия по выборке</td>
                <td>" . $var_ . "</td>
            </tr><tr>
                <td>Среднеквадратичное отклонение по генеральной совокупности</td>
                <td>" . $stdevp . "</td>
            </tr><tr>
                <td>Среднеквадратичное отклонение по выборке</td>
                <td>" . $stdev_ . "</td>
            </tr><tr>
                <td>Коэффициент вариации</td>
                <td>" . round(($k_var * 100), 2) . "%</td>
            </tr><tr>
                <td>Коэффициент осцилляции</td>
                <td>" . $k_osc . "</td>
            </tr><tr>
                <td>Прогноз</td>
                <td>" . $forecast->getData($algorithm) . "</td>
            </tr><tr>
                <td>Среднеквадратичное отклонение прогноза</td>
                <td></td>
            </tr><tr>
                <td>MAPE</td>
                <td></td>
            </tr>
            </table>
            <br />";
    
    return $_str;
}