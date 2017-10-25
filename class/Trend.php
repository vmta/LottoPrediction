<?php
/**
 * Description of Trend
 * 
 * Object calculates Trend based on the data provided.
 * 
 * Linear Trend is calculated as:
 *   Y = A + BXi
 *   where
 *   B = ( Σ(XiYi) - n * X(avg) * Y(avg) ) / ( Σ(Xi^2) - n * X(avg)^2 )
 *   A = Y(avg) - B * X(avg)
 * 
 * NOTE:
 * Since FullData object stores recent data in the beginning, actual B value
 * will be a mirrored reflection. To address this, array of values should have
 * been reverse sorted. However, this approach would break the graphical
 * representation when used in chart, since Chart object is to be provided
 * complex set of various data which would have to be reverse sorted as well,
 * and it would lead to more complexities. To sum up: should "B" ever be
 * displayed for whatever reason, it needs to be changed from positive to
 * negative and vice versa.
 *
 * @author vmta
 */
class Trend {
    
    // Hold data as Array for processing.
    // (Must be of type FullData).
    private $validData;
    public function getValidData() { return $this->validData; }
    
    // Hold Group ID as integer to retrieve correct numbers within specified
    // group, since object $validData holds full Data Table.
    // (Must be of type int).
    private $groupID;
    public function getGroupID() { return $this->groupID; }
    
    // Hold forecast frame value as integer.
    // (Must be of type int).
    private $forecastFrame;
    
    public function __construct($validData, $groupID, $forecastFrame) {
        $this->validData = $validData;
        $this->groupID = $groupID;
        $this->forecastFrame = $forecastFrame;
    }
    
    /**
     * An envelope method, which provides a simple switching capability in
     * case various Trend calculation algorithms shell be applied.
     * @param String $type
     * @return array
     */
    public function getData($algorithm) {
        switch($algorithm) {
            case "Linear":
                return $this->getLinear();
                break;
            default:
                print "<br>ERROR: Need to specify Trend algorithm.<br />";
        }
    }
    
    /**
     * Calculate LINEAR TREND.
     * 
     * Following issues must be addressed:
     * - calculate Σ(XiYi)
     * - calculate Σ(Xi^2)
     * - calculate X(avg) as (1 + n)/2
     * - get group average (FullData object allows to extract the value)
     * 
     * @return array
     */
    private function getLinear() {
        
        // We need to define constants A and B
        // Consider Y as a draw numbers and X, as a simple counter
        
        $gNums = $this->validData->getGroup($this->groupID);
        $n = count($gNums);
        $sum_XY = $this->sumXY($gNums);
        $sum_X2 = $this->sumX2($n);
        $Xavg = (1 + $n) / 2;
        $Yavg = $this->validData->getGroupAVG($this->groupID);
        $B = ( $sum_XY - $n * $Xavg * $Yavg ) / ( $sum_X2 - $n * pow($Xavg, 2));
        $A = $Yavg - $B * $Xavg;
        
        /**
        $data = "
                <table>
                <tr>
                    <td>TREND (Y = A + B * Xi)</td>
                    <td>n = ".$n."</td>
                </tr>
                <tr>
                    <td>A = Y(avg) - B * X(avg)</td>
                    <td>B = ( SUM(XiYi) - n * X(avg) * Y(avg) ) / ( SUM(POW(Xi,2)) - n * POW(X(avg), 2) )</td>
                </tr>
                <tr>
                    <td>sum_XY = ".$sum_XY."</td>
                    <td>sum_X2 = ".$sum_X2."</td>
                </tr>
                <tr>
                    <td>Xavg = ".$Xavg."</td>
                    <td>Yavg = ".$Yavg."</td>
                </tr>
                <tr>
                    <td>A = ".$A."</td>
                    <td>B = ".$B."</td>
                </tr>
                </table>
                ";
        print($data);
        **/
        
        $arrayY = array();
        
        //for($i = 0; $i < (count($gNums) + $this->forecastFrame); $i++) {
        for($i = 0; $i < count($gNums); $i++) {
            $Y = $A + $B * ($i + 1);
            array_push($arrayY, $Y);
        }
        return $arrayY;
    }
    
    private function sumXY($gNums) {
        $sum_XY = 0;
        for($i = 0; $i < count($gNums); $i++) {
            $sum_XY += ($i + 1) * $gNums[$i];
        }
        return $sum_XY;
    }
    
    private function sumX2($elements) {
        $sum_X2 = 0;
        for($i = 1; $i <= $elements; $i++) {
            $sum_X2 += $i * $i;
        }
        return $sum_X2;
    }
}