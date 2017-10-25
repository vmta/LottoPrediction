<?php
function displayPredictionFANN() {
    
    $str = "Необходимо провести рефакторинг данного раздела с учетом "
            . "следующих положений:<br />"
            . "<ul>"
            . "<li>выполнить извлечение номера из диапазона,</li>"
            . "<li>прогонка по нейронным сетям,</li>"
            . "<li>вычисление значения вероятности для номера,</li>"
            . "<li>сортировка значений по возростанию,</li>"
            . "<li>прогонка результирующей комбинации по нейронным сетям,</li>"
            . "<li>вычисление значения вероятности для комбинации.</li>"
            . "</ul>";
    
    ////////////////////////////////////////////////////////////////////////////
    /**
     * Get FullData Object containing all VALID data.
     */
    $validData = new FullData("VALID");
    
    /**
     * Save training data in file for later use.
     */
    $trainDataFile = getTrainDataFile($validData, 27);
    
    ////////////////////////////////////////////////////////////////////////////
    /**
     * Create (F)ANN Object with 3 layers, 6 input, 1200 hidden an 6 output
     * neurons.
     */
    $ann = new ANN(3, 6, 6000, 6);
    $_ann = new ANN(3, 6, 180, 6);
    
    /**
     * Check if (F)ANN is created and trained. If it is created but not trained,
     * then try to train it on file.
     */
    if($ann->isReady() && !$ann->isTrained()) {
        $ann->train($trainDataFile);
    }
    if($_ann->isReady() && !$_ann->isTrained()) {
        $_ann->train($trainDataFile);
    }
    
    /**
     * Check if (F)ANN is created and trained. If both are positive, then
     * proceed further.
     */
    if($ann->isReady() && $ann->isTrained()) {
        $str .= "<table>";
        
        /**
         * To get 5 combinations run 5 FOR cycles.
         */
        for($myDraws = 0; $myDraws < 5; $myDraws++) {
            
            /**
             * On each cycle run create an empty input array, which shall store
             * the numbers produced with the randomizer.
             */
            $input = [];
            
            /**
             * Set internal counter to 0.
             */
            $i = 0;
            
            /**
             * Run as many times as necessary in order to get the array filled
             * with unique numbers exactly LOTTERY_BALLS_NUMBER (i.e. => 6)
             * times.
             */
            while($i < LOTTERY_BALLS_NUMBER) {
                
                /**
                 * Store random number within the range from LOTTERY_MIN_NUMBER
                 * incremented by internal counter on each cycle run, to
                 * LOTTERY_MAX_NUMBER less the LOTTERY_BALLS_NUMBER and
                 * incremented by internal counter on each cycle run.
                 * { 1, (52 - 6 + 1) }
                 * ...
                 * { 6, (52 - 6 + 6) }
                 */
                $num = rand(
                            LOTTERY_MIN_NUMBER + $i,
                            LOTTERY_MAX_NUMBER - LOTTERY_BALLS_NUMBER + $i
                        );
                
                /**
                 * Initially each random number is believed to be unique.
                 */
                $unique = true;
                
                /**
                 * Set inner internal index counter to 0.
                 */
                $j = 0;
                
                /**
                 * Compare random number with each and every number already
                 * stored in an array.
                 */
                while($j < count($input)) {
                    
                    /**
                     * Compare new random number with array stored numbers.
                     */
                    if($num == $input[$j]) {
                        
                        /**
                         * If condition evaluates to equal, then new random
                         * number is not unique. Mark it as FALSE and break
                         * out of the loop.
                         */
                        $unique = false;
                        break;
                    }
                    
                    /**
                     * Increment inner internal index counter.
                     */
                    $j++;
                }
                
                /**
                 * If new number is unique, store it in array, and increment
                 * internal counter.
                 */
                if($unique) {
                    array_push($input, $num);
                    $i++;
                }
            }
            
            /**
             * Sort the resulting array, holding LOTTERY_BALLS_NUMBER random
             * numbers in ascending order.
             */
            sort($input);
            
            /**
             * Feed the resulting array with combination into the (F)ANN, and
             * retrieve prediction as percentile per each number.
             */
            $output = $ann->run($input);
            
            /**
             * DISPLAY COMBINATIONS
             * and percentages.
             */
            
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
        $ann1->train($trainDataFile);
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
            if($output[0] > 0.3) {
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
    
    return $str;
}

/**
 * Create training data file from the array limited by integer value. Return
 * file name to the callee.
 *  
 * @param array $validData
 * @param int $trainDataRecords
 * @return string
 */
function getTrainDataFile($validData, $trainDataRecords) {
    
    /**
     * File Path and Name.
     */
    $trainDataFile = dirname(__FILE__) . "/train.data";
    
    /**
     * Number of records (F)ANN will be trained on. If parameter was not set,
     * then consider a default of 10 records to be used.
     */
    $trainDataRecords = (isset($trainDataRecords)) ? $trainDataRecords : 10;
    
    /**
     * Try to open (or create, if no file is present) file for writing.
     */
    $fhandle = fopen($trainDataFile, "w");
    
    /**
     * Try to write data in a text format into the file.
     */
    fwrite($fhandle, $validData->getNumbersAsText($trainDataRecords));
    
    /**
     * Try to close file and free resources.
     */
    fclose($fhandle);
    
    /**
     * Return File Path and Name to the caller.
     */
    return $trainDataFile;
}