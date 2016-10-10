<?php
/**
 * Description of Probability
 *
 * @author vmta
 */
class Probability {
    var $m = 1;
    var $n = 1;
    
    function __construct($m, $n) {
        $this->m = $m;
        $this->n = $n;
    }
    
    function calculateSimple() {
        return round(1 / (new Combination($this->m, $this->n))->calculate(), 10);
    }
    
    function calculateBernulli() {
        $C_mn = (new Combination($this->m, $this->n))->calculate();
        $p_m = $this->m / (6 * $this->n);
        $q_m = 1 - $p_m;
        $P_mn = $C_mn * pow($p_m, $this->m) * pow($q_m, ($this->n - $this->m));
        return $P_mn;
    }
    
    function calculateLaplaceLocal() {
        $p = $this->calculateSimple(); // Get simple stat probability
        $q = 1 - $p;
        $e = 0.01; // By default set e = 1%
        $n = 100; // By default set n = 100
        $func = $e * sqrt($n) / sqrt($p * $q);
        //$rightSide = 2*F()
    }
    
    function calculateLaplaceIntegral() {}
    
    function calculateLaplaceDeviation() {}
}