<?php
/**
 * Description of Correlation
 *
 * @author vmta
 */
class Correlation {
    
    /**
     * Variable $data of type Array holding series of two
     * numbers to be compared.
     * 
     * @var Array $data
     */
    var $data;
    
    /**
     * Variable $pair of type String holds definition of which
     * numbers to compare.
     * 
     * @var String $pair
     */
    var $pair;
    
    public function __construct($data, $pair) {
        $this->data = $data;
        $this->pair = $pair;
    }
    
    /**
     * Re-setting $data, $pair should the need be
     * 
     * @param Array $data
     * @param String $pair
     */
    function setParams($data, $pair) {
        $this->data = $data;
        $this->pair = $pair;
    }
    
    /**
     * Helper Function which defines exactly what numbers
     * to process (ball_X)
     * 
     * @param String $pair
     * @return String ball_X
     */
    function getX() {
        return 'ball_' . substr($this->pair, 0, 1);
    }
    
    /**
     * Helper Function which defines exactly what numbers
     * to process (ball_Y)
     * 
     * @param String $pair
     * @return String ball_Y
     */
    function getY() {
        return 'ball_' . substr($this->pair, -1, 1);
    }
    
    /**
     * Calculate Pearson's Correlation Coefficient
     * for the given series/numbers.
     * 
     * @return float $Rxy (calculated coefficient)
     */
    function getPearson() {
        $Rxy = 0;
        $mX = 0;
        $mX2 = 0;
        $mY = 0;
        $mY2 = 0;
        $mXY = 0;
        $N = 0;
        foreach($this->data as $arr) {
            $x = $arr[$this->getX()];
            $y = $arr[$this->getY()];
            $mX += $x;
            $mX2 += $x * $x;
            $mY += $y;
            $mY2 += $y * $y;
            $mXY += $x * $y;
            $N++;
        }
        $mX /= $N;
        $mX2 /= $N;
        $mY /= $N;
        $mY2 /= $N;
        $mXY /= $N;
        $Rxy = ($mXY - $mX * $mY) / (sqrt($mX2 - $mX * $mX) * sqrt($mY2 - $mY * $mY));
        return $Rxy;
    }
    
    /**
     * Calculate Spearman's Correlation Coefficient for the given
     * series/numbers.
     * 
     * First, sort the array and rate corresponding data. The latter
     * then construes a new rating array and then this array is to
     * be calculated by passing to Pearson's calculation method.
     * 
     * @return float (get) Pearson's Correlation Coefficient
     */
    function getSpearman() {
        $xsorted = array();
        $ysorted = array();
        $xties = (object) array();
        $yties = (object) array();

        foreach($this->data as $arr) {
            // Setting first serie
            $x = $arr[$this->getX()];
            array_push($xsorted, $x);
            if(!isset($xties->{$x}))
                $xties->{$x} = new RankedPoint($x);
            else
                $xties->{$x}->increaseCount();
            // Setting second serie
            $y = $arr[$this->getY()];
            array_push($ysorted, $y);
            if(!isset($yties->{$y}))
                $yties->{$y} = new RankedPoint($y);
            else 
                $yties->{$y}->increaseCount();
        }
        // Reverse sorting
        rsort($xsorted);
        rsort($ysorted);
        // Apply ranks
        for($i = 0; $i < count($xsorted); $i++) {
            if($xties->{$xsorted[$i]}->getRank() == 0)
                $xties->{$xsorted[$i]}->setRank((($i + 1) + ($i + 1) + $xties->{$xsorted[$i]}->getCount() - 1) / 2);
            if($yties->{$ysorted[$i]}->getRank() == 0)
                $yties->{$ysorted[$i]}->setRank((($i + 1) + ($i + 1) + $yties->{$ysorted[$i]}->getCount() - 1) / 2);
        }
        // Create array of ranks
        $resArray = array();
        for($i = 0; $i < count($xsorted); $i++) {
            $tmpArray = array("ball_1" => $xties->{$xsorted[$i]}->getRank(), "ball_2" => $yties->{$ysorted[$i]}->getRank());
            array_push($resArray, $tmpArray);
        }
        // Return Pearson's coefficient for ranked series
        $this->setParams($resArray, "1-2");
        return $this->getPearson();
    }
}

class Point {
    var $ball_1;
    var $ball_2;
    public function __construct($x, $y) {
        $this->ball_1 = $x;
        $this->ball_2 = $y;
    }
}

class RankedPoint {
    var $value;
    var $count;
    var $rank;
    public function __construct($value) {
        $this->value = $value;
        $this->count = 1;
        $this->rank = 0;
    }
    
    function getCount() {
        return $this->count;
    }
    
    function getRank() {
        return $this->rank;
    }
    
    function setRank($rank) {
        $this->rank = $rank;
    }
    
    function increaseCount() {
        $this->count += 1;
    }
}