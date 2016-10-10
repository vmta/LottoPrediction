<?php
/**
 * Description of FullData
 * 
 * FullData Object is intended for
 * holding and processing complete
 * DB data. 
 *
 * @author vmta
 */
class FullData {
    
    // Hold retrieved data as Array.
    private $dbData;
    
    /** Provide access to retrieved data.
     * 
     * @return Array $dbData
     */
    public function get() { return $this->dbData; }
    
    /**
     * Class constructor takes parameter in
     * the form of an SQL query and proceeds
     * with data retrieval.
     * 
     * @param string $query
     */
    public function __construct($dataSet) {
        
        /**
         * Default SQL query only retrieves VALID
         * data, i.e. records that have their ID
         * greater than 917 (as of 2016-10-06).
         * 
         * Check if parameter is not empty and
         * equals ALL, then retrieve all available
         * data from DB, otherwise only VALID data
         * will be retrieved.
         */
        if(!isset($dataSet) || empty($dataSet) || $dataSet != "ALL") {
            $query = "SELECT * FROM `full` WHERE `id` > 917 ORDER BY `id` DESC;";
        } else {
            $query = "SELECT * FROM `full` ORDER BY `id` DESC;";
        }
        
        /**
         * Query the DataBase. On success returns
         * raw data, else dies with error message.
         */
        $queryResult = mysql_query($query)
            or die("Could not perform ".$query."<br />".mysql_error()."<br />");
        
        /**
         * Initialize and populate object member
         * with data from DataBase as an array.
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
        $data = $this->get();
        $ids = array();
        foreach($data as $row) {
            array_push($ids, $row["id"]);
        }
        return $ids;
    }
    
    /**
     * Provide access to an array of Group {1,2,3,4,5,6}
     * numbers.
     * @param type $index
     * @return array
     */
    function getGroup($index) {
        $data = $this->get();
        $group = array();
        foreach($data as $row) {
            array_push($group, $row["ball_".$index]);
        }
        return $group;
    }
    
    /**
     * Locate MINIMUM number value for the Group.
     * @param type $index
     * @return type
     */
    function getGroupMIN($index) {
        $group = $this->getGroup($index);
        sort($group);
        return $group[0];
    }
    
    /**
     * Locate MAXIMUM number value for the Group.
     * @param type $index
     * @return type
     */
    function getGroupMAX($index) {
        $group = $this->getGroup($index);
        rsort($group);
        return $group[0];
    }
    
    /**
     * Calculate AVERAGE number value for the Group.
     * @param type $index
     * @return type
     */
    function getGroupAVG($index) {
        $group = $this->getGroup($index);
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
     * Calculate Simple Moving Average (SMA)
     * for the Group.
     * @param type $index
     * @param type $aggregator
     * @return array
     */
    function getGroupSMA($index, $aggregator) {
        $group = $this->getGroup($index);
        $sma = array();
        $sum = 0;
        $counter = 1;
        foreach($group as $value) {
            if($counter % $aggregator != 0) {
                $sum += $value;
            } else {
                $sum += $value;
                array_push($sma,($sum / $aggregator));
                $sum = 0;
            }
            $counter++;
        }
        return $sma;
    }
}