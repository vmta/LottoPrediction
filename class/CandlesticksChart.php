<?php
/**
 * Description of CandlestickChart
 *
 * @author vmta
 */
class CandlesticksChart extends Chart {
    
    function __construct($containerWidth, $containerHeight) {
        parent::__construct(uniqid(), $containerWidth, $containerHeight);
    }
    
    public function draw($data) {
        $str = $this->getPreloadScript();
        $dataStr = $this->dataConstructor($data);
        var_dump($dataStr);
        $str .= "<script type=\"text/javascript\">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization);
                
                function drawVisualization() {
                    var data = google.visualization.arrayToDataTable(["
                                .$this->dataConstructor($data)."]);
                    var options = ".$this->optionsConstructor("candlesticks").";
                    var chart = new google.visualization.ComboChart(
                    document.getElementById('".$this->getContainerID()."'));
                    chart.draw(data, options);
                }
                </script>";
        $str .= $this->getContainer();
        return $str;
    }
}