<?php
/**
 * Description of Forecast
 *
 * @author vmta
 */
class Forecast {
    
    // Hold trend data for processing.
    // (must be of type Trend).
    private $trend;
    
    public function __construct($trend) {
        $this->trend = $trend;
    }
    
    public function getData($algorithm) {
        switch($algorithm) {
            case "Linear":
                return $this->getLinear();
                break;
            default:
                print "<br>ERROR: Need to specify Trend algorithm.<br />";
        }
    }
    
    private function getLinear() {
        $groupID = $this->trend->getGroupID();
        $realNumbers = $this->trend->getValidData()->getGroup($groupID);
        
        //var_dump($realNumbers);
        
        return "RealNumbers: ".$realNumbers;
    }
    
}