<?php
/**
 * Description of GroupOfNumbers
 * 
 * Object of this class holds Array of
 * distinct serie of lottery result numbers.
 * 
 * Class is a natural extension of class
 * LotteryNumbers, which holds the numbers
 * (can be access with get() method).
 *
 * @author vmta
 */
class GroupOfNumbers extends LotteryNumbers {
    
    /** Local variable and access methods.
     *
     * @var int Value ranges from 1 to 6. 
     */
    private $groupID;
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }
    public function getGroupID() {
        return $this->groupID;
    }
    
    public function __construct($groupID, $sqlOptions, $draws) {
        
        // Check if parameter is not empty and
        // is within allowed range: 0 < $id < 7.
        if(empty($groupID) || $groupID < 1) {
            $groupID = 1;
        } elseif($groupID > 6) {
            $groupID = 6;
        }
        
        // Check if parameter was passed, if it
        // was not, then set an empty variable.
        if(!isset($sqlOptions) || empty($sqlOptions)) {
            $sqlOptions = " ORDER BY `id` DESC ";
        }
        
        // Check if perameter was passed, if it
        // was not, then to default of 50.
        if(!isset($draws) || $draws < 1) {
            $draws = 50;
        }
        $sqlOptions .= " LIMIT " . $draws;
        
        // Initialize local variable
        $this->setGroupID($groupID);
        
        // Prepare SQL query.
        $query = "SELECT `ball_" . $groupID . "` AS `" . $groupID . "` FROM `full` WHERE `id` > 917 " . $sqlOptions;
        
        // Initialize object of parent class
        // passing SQL query as parameter.
        parent::__construct($query);
    }
    
    /**
     * Find minimum value for the group.
     * 
     * @return int Value ranges from 1 to 52.
     */
    public function getMIN() {
        $numbers = $this->get();
        $valueMIN = LOTTERY_MAX_NUMBER;
        for($i = 0; $i < count($numbers); $i++) {
            if($valueMIN > $numbers[$i][$this->getGroupID()]) {
                $valueMIN = $numbers[$i][$this->getGroupID()];
            }
        }
        return $valueMIN;
    }
    
    /**
     * Find maximum value for the group.
     * 
     * @return int Value ranges from 1 to 52.
     */
    public function getMAX() {
        $numbers = $this->get();
        $valueMAX = LOTTERY_MIN_NUMBER;
        for($i = 0; $i < count($numbers); $i++) {
            if($valueMAX < $numbers[$i][$this->getGroupID()]) {
                $valueMAX = $numbers[$i][$this->getGroupID()];
            }
        }
        return $valueMAX;
    }
    
    /**
     * Find average value for the group.
     * 
     * @return double Value ranges from 1 to 52.
     */
    public function getAVG() {
        $numbers = $this->get();
        $valueSUM = 0;
        for($i = 0; $i < count($numbers); $i++) {
            $valueSUM += $numbers[$i][$this->getGroupID()];
        }
        $valueAVG = $valueSUM / count($numbers);
        return $valueAVG;
    }
}