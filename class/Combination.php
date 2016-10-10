<?php
/**
 * Description of Combination
 *
 * @author vmta
 */
class Combination {
    var $m = 1;
    var $n = 1;
    
    public function __construct($m, $n) {
        $this->m = $m;
        $this->n = $n;
    }
    
    function calculate() {
        return gmp_intval(gmp_fact($this->n) / (gmp_fact($this->n - $this->m) * gmp_fact($this->m)));
    }
}