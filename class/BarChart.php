<?php
/**
 * Description of BarChart
 *
 * @author vmta
 */
class BarChart extends Chart {
    
    function __construct($containerWidth, $containerHeight) {
        parent::__construct(uniqid(), $containerWidth, $containerHeight);
    }
    
    public function draw($data) {
        $str = $this->getPreloadScript();
        $str .= "<script type=\"text/javascript\">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization);
                
                function drawVisualization() {
                    var data = google.visualization.arrayToDataTable(["
                                .$this->dataConstructor($data)."]);
                    var options = ".$this->optionsConstructor("bars").";
                    var chart = new google.visualization.ComboChart(
                    document.getElementById('".$this->getContainerID()."'));
                    chart.draw(data, options);
                }
                </script>";
        $str .= $this->getContainer();
        return $str;
    }
}