<?php
function displayPrediction() {
    
    $fullData = new FullData("ALL");
    $validData = new FullData("VALID");
    
    $ids = $validData->getIDs();
    $groups = array();
    for($i = LOTTERY_MIN_NUMBER; $i < LOTTERY_BALLS_NUMBER + 1; $i++) {
        array_push($groups, $validData->getGroup($i));
    }
    
    //var_dump($validData->getGroupMIN(1));
    /**
    print "MIN(1):".$validData->getGroupMIN(1)
            ."; MAX(1):".$validData->getGroupMAX(1)
            ."; AVG(1):".$validData->getGroupAVG(1)
            ."<br />";
    */
    /**
    $sma = $validData->getGroupSMA(1, 5);
    foreach($sma as $value) {
        print "SMA(1, 5):".$value."<br />";
    }
    */
    $str = "";
    $chart = new Chart();
    $groupIDs = [1, 2, 3, 4, 5, 6];
    $aggregators = [50, 25, 10];
    
    $str .= "<table width=100%>";
    foreach($groupIDs as $groupID) {
        $str .= "<tr>";
        foreach($aggregators as $aggregator) {
            $sma = $validData->getGroupSMA($groupID, $aggregator);
            $str .= "<td width=33%>" . $chart->gDrawSMA($sma, $groupID, $aggregator) . "</td>";
        }
        $str .= "</tr>";
    }
    $str .= "</table>";
    
    $str .= "<script>
        // Predefined variables
        var lotteryMin = " . LOTTERY_MIN_NUMBER . ";
        var lotteryMax = " . LOTTERY_MAX_NUMBER . ";
        var lotteryBalls = " . LOTTERY_BALLS_NUMBER . ";
        
        // DEBUG only
        var doDebug = false;
        function debug(data) {
            document.getElementById('logDisplay').innerHTML += data + '<br>';
        }
        
        // Take an array as parameter and check
        // if the size of array equals to predefined
        // quantifier (lotteryBalls) and return true
        // or false otherwise.
        function isDone(predictedNumbers) {
            if(predictedNumbers.length != lotteryBalls) {
                return false;
            } else {
                return true;
            }
        }
        
        // Take an integer as parameter and return
        // random integer within range (index to
        // lotteryMax - lotteryBalls + index)
        // i.e. for (index=1) => range is (1, 52-6+1)
        function getRandomNumber(index) {
            return Math.floor((Math.random() * (lotteryMax - lotteryBalls)) + index);
        }
        
        // Take a newly generated number and compare
        // it to a stored array values, cycled in a
        // loop. Returns true in case numbers are
        // equal or false otherwise.
        function isEqual(number, comparative) {
            if(number != comparative) {
                return false;
            } else {
                return true;
            }
        }
        
        // Generate random numbers in a loop.
        // Prior to inserting generated number into
        // an array, check if the number has no
        // duplicates. Once array has lotteryBalls
        // amount of distinct numbers, exit the loop,
        // sort an array in ascending order and display
        // the combination by prepending to any already
        // displayed combinations.
        function getRandomNumbers() {
            var predictedNumbers = [];
            var index = lotteryMin;
            while(!isDone(predictedNumbers)){
                var newNumber = getRandomNumber(index);
                if(predictedNumbers.length > 0) {
                    var equalNumbers = false;
                    for(var i = 0; i < predictedNumbers.length; i++) {
                        if(isEqual(newNumber, predictedNumbers[i])) {
                            equalNumbers = true;
                        }
                    }
                    if(!equalNumbers) {
                        predictedNumbers.push(newNumber);
                    }
                } else {
                    predictedNumbers.push(newNumber);
                }
                index++;
            }
            predictedNumbers.sort(function (a, b) {
                return a - b;
            });
            
            var newHTML = '';
            for(var i = 0; i < predictedNumbers.length; i++) {
                newHTML += predictedNumbers[i];
                if(i < predictedNumbers.length - 1) {
                    newHTML += ', ';
                }
            }
            
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        </script>";
    
    $str .= "<p>ToDo:</p>
        <ol>
            <li>При генерации комбинации учесть:</li>
            <ul>
                <li>Историческую статистику (мин/макс/среднее)</li>
                <li>Относительную частоту</li>
                <li>Коэффициенты корреляции</li>
            </ul>
            <li>После генерации комбинации рассчитать:</li>
            <ul>
                <li>Вероятность</li>
                <li>Коэффициенты корреляции (с учетом новых данных)</li>
                <li>Отклонение от средних значений</li>
            </ul>
        </ol>
        
        <span onclick='getRandomNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Показать номера</span>
        <br /><span id='predictionNumbersDisplay'></span>
        <br /><span id='logDisplay'></span>
        ";
    
    return $str;
}