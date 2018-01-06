<?php
/**
 * Description of LaplaceConstant
 *
 * @author vmta
 */
class LaplaceConstant {
    
    private $_X;
    private $queryLP;
    
    function __construct($_X) {
        $this->_X = $_X;
        $this->queryLP = "SELECT `F(x)` FROM `LaplaceConstants` WHERE `x`=" . $this->_X;
    }
    
    private function getDataFromDB() {
        $q = mysqli_query($dbCon, $this->queryLP);
                //or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
        $r = mysqli_fetch_row($q);
        $res = ($r == NULL) ? "0" : $r[0];
        return $res;
    }
    
    public function getFx() {
        return $this->getDataFromDB();
    }
}
