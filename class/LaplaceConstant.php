<?php
class LaplaceConstant {
    
    var $_X;
    var $queryLP;
    
    function __construct($_X) {
        $this->_X = $_X;
        $this->queryLP = "SELECT `F(x)` FROM `LaplaceConstants` WHERE `x`=" . $this->_X;
    }
    
    function getDataFromDB() {
        $q = mysql_query($this->queryLP)
                or die("Could not perform ".$query."<br />".mysql_error()."<br />");
        $r = mysql_fetch_row($q);
        $res = ($r == NULL) ? "0" : $r[0];
        return $res;
    }
    
    public function getFx() {
        return $this->getDataFromDB();
    }
}