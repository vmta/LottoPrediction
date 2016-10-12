<?php
/**
 * Description of Average
 *
 * @author vmta
 */
class Average extends GroupOfNumbers {
    
    /**
     * How many draws (numbers) to get an average
     * from.
     * 
     * @var int Value represents period (in draws)
     */
    private $draws;
    public function setDraws($draws) { $this->draws = $draws; }
    public function getDraws() { return $this->draws; }
    
    /**
     * Locally obtained array of averages.
     *
     * @var Array
     */
    private $averages;
    public function setAverages() {}
    public function getAverages() { $this->averages; }
    
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
        
        // Initialize local variable.
        $this->setDraws($draws);
        
        // Initialize object of parent class
        // passing parameters.
        parent::__construct($groupID, $sqlOptions, $draws);
    }
    
    /**
     * Find simple moving average (SMA) value for the group.
     * 
     * @return double Value ranges from 1 to 52.
     */
    public function getSMA() {
        $numbers = $this->get();
        $averages = array();
        $valueSUM = 0;
        for($i = 0; $i < count($numbers); $i++) {
            if($i % $this->getDraws() != 0) {
                $valueSUM += $numbers[$i][$this->getGroupID()];
            } else {
                array_push($averages, ($valueSUM / $this->draws));
                $valueSUM = 0;
            }
        }
        return $averages;
    }
}