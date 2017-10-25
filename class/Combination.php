<?php
/**
 * Description of Combination
 *
 * @author vmta
 */
class Combination {
    private $m = 1;
    private $n = 1;
    
    function __construct($m, $n) {
        $this->m = $m;
        $this->n = $n;
    }
    
    function calculate() {
        return gmp_intval(gmp_fact($this->n) / (gmp_fact($this->n - $this->m) * gmp_fact($this->m)));
    }
}