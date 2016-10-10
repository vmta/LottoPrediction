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
    private $period;
    public function setPeriod($period) { $this->period = $period; }
    public function getPeriod() { return $this->period; }
    
    /**
     * Locally obtained array of averages.
     *
     * @var Array
     */
    private $averages;
    public function setAverages() {}
    public function getAverages() { $this->averages; }
    
    public function __construct($groupID, $period) {
        $this->setPeriod($period);
        parent::__construct($groupID);
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
            if($i % $this->period != 0) {
                $valueSUM += $numbers[$i][$this->getGroupID()];
            } else {
                array_push($averages, ($valueSUM / $this->period));
                $valueSUM = 0;
            }
        }
        return $averages;
    }
}