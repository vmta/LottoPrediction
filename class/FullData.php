<?php
/**
 * Description of FullData
 * 
 * FullData Object is intended for holding and processing complete DB data. 
 *
 * @author vmta
 */
class FullData {
    
    // Hold retrieved data as Array.
    private $dbData;
    
    /**
     * Provide access to retrieved data.
     * @return array
     */
    public function get() { return $this->dbData; }
    
    /**
     * Class constructor takes parameter in the form of an SQL query and
     * proceeds with data retrieval.
     * @param string $dataSet
     */
    public function __construct($dataSet) {
        
        /**
         * Default SQL query only retrieves VALID data, i.e. records that have
         * their ID greater than 917 (as of 2016-10-06).
         * 
         * Check if parameter is not empty and equals ALL, then retrieve all
         * available data from DB, otherwise only VALID data will be retrieved.
         */
        if(!isset($dataSet) || empty($dataSet) || $dataSet === "VALID") {
            $query = "SELECT * FROM `full` WHERE `id` > 917 ORDER BY `id` DESC;";
        } elseif($dataSet === "ALL") {
            $query = "SELECT * FROM `full` ORDER BY `id` DESC;";
        } else {
            $query = $dataSet;
        }
        
        /**
         * Query the DataBase. On success returns raw data, else dies with
         * error message.
         */
        $queryResult = mysql_query($query)
            or die("Could not perform ".$query."<br />".mysql_error()."<br />");
        
        /**
         * Initialize and populate object member with data from DataBase as
         * an array.
         */
        $this->dbData = array();
        while($row = mysql_fetch_array($queryResult, MYSQL_ASSOC)) {
            array_push($this->dbData, $row);
        }
        
        /**
         * Free resources: SQL query result.
         */
        mysql_free_result($queryResult);
    }
    
    /**
     * Provide access to an array of ID numbers.
     * @return array
     */
    function getIDs() {
        return array_column($this->get(), "id");
    }
    
    /**
     * Provide access to an array of Group {1,2,3,4,5,6} numbers.
     * @param int $groupID
     * @return array
     */    
    function getGroup($groupID) {
        return array_column($this->get(), "ball_".$groupID);
    }
    
    /**
     * Locate MINIMUM number value for the Group.
     * @param int $groupID
     * @return int
     */
    function getGroupMIN($groupID) {
        $group = $this->getGroup($groupID);
        sort($group);
        return $group[0];
    }
    
    /**
     * Locate MAXIMUM number value for the Group.
     * @param int $groupID
     * @return int
     */
    function getGroupMAX($groupID) {
        $group = $this->getGroup($groupID);
        rsort($group);
        return $group[0];
    }
    
    /**
     * Calculate AVERAGE number value for the Group.
     * @param int $groupID
     * @return int
     */
    function getGroupAVG($groupID) {
        $group = $this->getGroup($groupID);
        $sum = 0;
        $counter = 0;
        foreach($group as $value) {
            $sum += $value;
            $counter++;
        }
        if($counter != 0) {
            return round($sum / $counter);
        } else {
            return null;
        }
    }
    
    /**
     * Calculate Simple Moving Average (SMA) for the Group. (Define average for
     * divided groups.)
     * @param int $groupID
     * @param int $aggregator
     * @return array
     */
    function getGroupSMA($groupID, $aggregator) {
        $ids = $this->getIDs();
        $group = $this->getGroup($groupID);
        $sma = array();
        while(count($group) > 0) {
            $sum = 0;
            for($i = 0; $i < $aggregator; $i++) {
                $sum += $group[$i];
            }
            array_push($sma, ["id" => array_shift($ids), "SMA" => ($sum / $aggregator)]);
            array_shift($group);
        }
        return $sma;
    }
    
    /**
     * Calculate Weighted Moving Average (WMA) for the Group. (Define average
     * for divided groups with applied weight.)
     * @param int $groupID
     * @param int $aggregator
     * @return array
     */
    function getGroupWMA($groupID, $aggregator) {
        $ids = $this->getIDs();
        $group = $this->getGroup($groupID);
        $wma = array();
        $denominator = gmp_strval(gmp_fact($aggregator));
        while(count($group) > 0) {
            $sum = 0;
            $factor = $aggregator;
            for($i = 0; $i < $aggregator; $i++) {
                $sum += $group[$i] * $factor;
                $factor--;
            }
            array_push($wma, ["id" => array_shift($ids), "WMA" => ($sum / $denominator)]);
            array_shift($group);
        }
        return $wma;
    }
    
    /**
     * Merge all groups numbers into one flat Array.
     * @return array
     */
    private function mergeNumbers() {
        $bigData = array_merge(
                $this->getGroup(1),
                $this->getGroup(2),
                $this->getGroup(3),
                $this->getGroup(4),
                $this->getGroup(5),
                $this->getGroup(6));
        return $bigData;
    }
    
    /**
     * Count appearances of each number in merged array.
     * @return array
     */
    private function getNumbersFrequency() {
        $bigData = $this->mergeNumbers();
        return array_count_values($bigData);
    }
    
    /**
     * Get specified quantity of least frequently appearing numbers.
     * @param int $quantity
     * @return array
     */
    function getLeastFrequentNumbers($quantity) {
        $counted = $this->getNumbersFrequency();
        asort($counted, SORT_NUMERIC);
        $leastValues = array();
        $index = 0;
        foreach($counted as $key=>$value) {
            if($index < $quantity) {
                array_push($leastValues, $key);
                $index++;
            } else {
                break;
            }
        }
        sort($leastValues);
        return $leastValues;
    }
    
    /**
     * Get specified quantity of most frequently appearing numbers.
     * @param int $quantity
     * @return array
     */
    function getMostFrequentNumbers($quantity) {
        $counted = $this->getNumbersFrequency();
        arsort($counted, SORT_NUMERIC);
        $mostValues = array();
        $index = 0;
        foreach($counted as $key=>$value) {
            if($index < $quantity) {
                array_push($mostValues, $key);
                $index++;
            } else {
                break;
            }
        }
        sort($mostValues);
        return $mostValues;
    }
    
    /**
     * Define how many draws ago a number was last drawn.
     * Loop through valid range of numbers (1 through 52)
     *   Loop through each row in bigData
     *     Loop through groups and lookup for a number
     *       If number matches the one in bigData, put difference of last draw
     *       ID and the ID of this number in bigData into holding array.
     *       As lookup for this particular number is over, break out of two
     *       nested loops.
     *   Continue with another number.
     * 
     * @return array
     */
    function getNumbersLatency() {
        $bigData = $this->get();
        $latestDrawID = array_shift($this->getIDs());
        $latentNumbers = array_fill(1, 52, 0);
        for($number = LOTTERY_MIN_NUMBER; $number < LOTTERY_MAX_NUMBER + 1; $number++) {
            foreach($bigData as $row) {
                for($groupID = LOTTERY_MIN_NUMBER; $groupID <LOTTERY_BALLS_NUMBER + 1; $groupID++) {
                    if($number == $row["ball_".$groupID]) {
                        $latentNumbers[$number] = $latestDrawID - $row["id"];
                        break 2;
                    }
                }
            }
        }
        return $latentNumbers;
    }
    
    /**
     * Get specified quantity of least latent numbers.
     * @param int $quantity
     * @return array
     */
    function getLeastLatentNumbers($quantity) {
        $counted = $this->getNumbersLatency();
        asort($counted, SORT_NUMERIC);
        $leastLatentNumbers = array();
        $index = 0;
        foreach($counted as $key=>$value) {
            if($index < $quantity) {
                array_push($leastLatentNumbers, $key);
                $index++;
            } else {
                break;
            }
        }
        sort($leastLatentNumbers);
        return $leastLatentNumbers;
    }
    
    /**
     * Get specified quantity of most latent numbers.
     * @param int $quantity
     * @return array
     */
    function getMostLatentNumbers($quantity) {
        $counted = $this->getNumbersLatency();
        arsort($counted, SORT_NUMERIC);
        $mostLatentNumbers = array();
        $index = 0;
        foreach($counted as $key=>$value) {
            if($index < $quantity) {
                array_push($mostLatentNumbers, $key);
                $index++;
            } else {
                break;
            }
        }
        sort($mostLatentNumbers);
        return $mostLatentNumbers;
    }
}