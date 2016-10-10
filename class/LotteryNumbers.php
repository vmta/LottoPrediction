<?php
/**
 * Description of LotteryNumbers
 * 
 * LotteryNumbers Object is intended for
 * holding and processing lottery numbers. 
 *
 * @author vmta
 */
class LotteryNumbers {
    
    // Hold retrieved data as Array.
    private $numbers;
    
    /** Provide access to retrieved data.
     * 
     * @return Array $numbers
     */
    public function get() { return $this->numbers; }
    
    /**
     * Class constructor takes parameter in
     * the form of an SQL query and proceeds
     * with data retrieval.
     * 
     * @param string $query
     */
    public function __construct($query) {
        
        // Check if parameter is empty and if
        // positive, provide default query
        // for data retrieval.
        //
        // 917 is a "magic" number for the
        // DataBase as earlier data is outdated
        // (as of 2016-10-06).
        if(empty($query))
            $query = "SELECT * FROM `full` WHERE `id` > 917 ORDER BY `id` DESC;";
        
        // Query the DataBase. On success
        // returns raw data, else dies with
        // error message.
        $queryResult = mysql_query($query)
            or die("Could not perform ".$query."<br />".mysql_error()."<br />");
        
        // Initialize and populate object member
        // with data from DataBase as an array.
        $this->numbers = array();
        while($row = mysql_fetch_array($queryResult, MYSQL_ASSOC)) {
            array_push($this->numbers, $row);
        }
        
        // Free resources: SQL query result
        mysql_free_result($queryResult);
    }
}