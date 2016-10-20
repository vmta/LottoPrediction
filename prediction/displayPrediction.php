<?php
function displayPrediction() {
    
    $str = "";
    
    /**
     * Get FullData Object containing all VALID data.
     */
    $validData = new FullData("VALID");
    
    /**
     * Save training data for later use.
     */
    $trainData = dirname(__FILE__) . "/train.data";
    $fhandle = fopen($trainData, "w");
    fwrite($fhandle, $validData->getNumbersAsText());
    fclose($fhandle);
    
    $ann = new ANN();
    if($ann->isReady() && !$ann->isTrained()) {
        $ann->train($trainData);
    }
    
    if($ann->isReady() && $ann->isTrained()) {
        $str .= "<table>";
        for($myDraws = 0; $myDraws < 5; $myDraws++) {
            $input = [];
            $i = 0;
            while($i < LOTTERY_BALLS_NUMBER) {
                $num = rand(
                            LOTTERY_MIN_NUMBER + $i,
                            LOTTERY_MAX_NUMBER - LOTTERY_BALLS_NUMBER + $i
                        );
                $unique = true;
                $j = 0;
                while($j < count($input)) {
                    if($num == $input[$j]) {
                        $unique = false;
                        break;
                    }
                    $j++;
                }
                if($unique) {
                    array_push($input, $num);
                    $i++;
                }
            }
            sort($input);
            
            /**
             * DISPLAY COMBINATIONS
             * and percentages.
             */
            $output = $ann->run($input);
            $str .= "<tr>";
            for($indx = 0; $indx < count($input); $indx++) {
                $str .= "<td><b>" . $input[$indx] . "</b> (" . round(($output[$indx]*100),2) . "%)</td>";
            }
            $str .= "</tr>";
        }
        $str .= "</table>";
    }
    
    $ann1 = new ANN(3, 6, 60, 1);
    if($ann1->isReady() && !$ann1->isTrained()) {
        $ann1->train($trainData);
    }
    if($ann1->isReady() && $ann1->isTrained()) {
        
        $input = [];
        $output = [];
        $isNotFound = true;
        
        while($isNotFound) {
        //    set_time_limit(10);
            $i = 0;
            while($i < LOTTERY_BALLS_NUMBER) {
                $num = rand(
                            LOTTERY_MIN_NUMBER + $i,
                            LOTTERY_MAX_NUMBER - LOTTERY_BALLS_NUMBER + $i
                        );
                $unique = true;
                $j = 0;
                while($j < count($input)) {
                    if($num == $input[$j]) {
                        $unique = false;
                        break;
                    }
                    $j++;
                }
                if($unique) {
                    array_push($input, $num);
                    $i++;
                }
            }
            sort($input);
            $output = $ann1->run($input);
            if($output[0] > 0.4) {
                $isNotFound = false;
            }
        }
        
        /**
         * DISPLAY COMBINATIONS
         * and percentages.
         */
        $str .= "<table><tr>";
        for($indx = 0; $indx < count($input); $indx++) {
            $str .= "<td><b>" . $input[$indx] . "</b></td>";
        }
        $str .= "<td>(" . round(($output[0]*100),2) . "%)</td>";
        $str .= "</tr></table>";
    }
    
    $validMIN = "";
    $validAVG = "";
    $validMAX = "";
    for($groupID = LOTTERY_MIN_NUMBER; $groupID < LOTTERY_BALLS_NUMBER + 1; $groupID++) {
        $validMIN .= $validData->getGroupMIN($groupID).";";
        $validAVG .= $validData->getGroupAVG($groupID).";";
        $validMAX .= $validData->getGroupMAX($groupID).";";
    }
    
    // Prepare least/most Frequent Numbers
    $leastFrequentNumbers = $validData->getLeastFrequentNumbers(LOTTERY_BALLS_NUMBER);
    $leastFrequentNUM = "";
    foreach($leastFrequentNumbers as $num) {
        $leastFrequentNUM .= $num.";";
    }
    $mostFrequentNumbers = $validData->getMostFrequentNumbers(LOTTERY_BALLS_NUMBER);
    $mostFrequentNUM = "";
    foreach($mostFrequentNumbers as $num) {
        $mostFrequentNUM .= $num.";";
    }
    
    // Prepare least/most Latent Numbers
    $leastLatentNumbers = $validData->getLeastLatentNumbers(LOTTERY_BALLS_NUMBER);
    $leastLatentNUM = "";
    foreach($leastLatentNumbers as $num) {
        $leastLatentNUM .= $num.";";
    }
    $mostLatentNumbers = $validData->getMostLatentNumbers(LOTTERY_BALLS_NUMBER);
    $mostLatentNUM = "";
    foreach($mostLatentNumbers as $num) {
        $mostLatentNUM .= $num.";";
    }
    
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
                newHTML += predictedNumbers[i] + ';';
            }
            
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getMinNumbers() {
            var newHTML = '".$validMIN." (мин)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getAvgNumbers() {
            var newHTML = '".$validAVG." (средние)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getMaxNumbers() {
            var newHTML = '".$validMAX." (макс)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
         
        function getLeastFrequentNumbers() {
            var newHTML = '".$leastFrequentNUM." (редкие)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getMostFrequentNumbers() {
            var newHTML = '".$mostFrequentNUM." (частые)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getLeastLatentNumbers() {
            var newHTML = '".$leastLatentNUM." (менее латентные)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function getMostLatentNumbers() {
            var newHTML = '".$mostLatentNUM." (наиболее латентные)';
            var prevHTML = document.getElementById('predictionNumbersDisplay').innerHTML;
            if(prevHTML != '') {
                newHTML += '<br />' + prevHTML;
            }
            document.getElementById('predictionNumbersDisplay').innerHTML = newHTML;
        }
        
        function clearNumbers() {
            document.getElementById('predictionNumbersDisplay').innerHTML = '';
        }
        </script>";
    
    $str .= "<p>ToDo:<br />
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
        </ol></p>
        
        <p><span onclick='getRandomNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Случайные номера</span><br />
        <span onclick='getMinNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Минимальные номера</span> | 
        <span onclick='getAvgNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Усредненные номера</span> | 
        <span onclick='getMaxNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Максимальные номера</span><br />
        <span onclick='getLeastFrequentNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Редко выпадающие номера</span> | 
        <span onclick='getMostFrequentNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Часто выпадающие номера</span><br />
        <span onclick='getLeastLatentNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Наименее латентные номера</span> | 
        <span onclick='getMostLatentNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Наиболеее латентные номера</span><br />
        <span onclick='clearNumbers();' style='border:1px solid #aabbcc' onmouseover='this.style.border=\"1px solid #66ccdd\";this.style.backgroundColor=\"#66ccdd\"' onmouseout='this.style.border=\"1px solid #aabbcc\";this.style.backgroundColor=\"#ffffff\"'>Очистить</span>
        <br /><span id='predictionNumbersDisplay'></span>
        <br /><span id='logDisplay'></span></p>";
    
    return $str;
}