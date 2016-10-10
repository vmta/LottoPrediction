<?php
/**
 * Description of Chart
 * 
 * @author vmta
 */
class Chart {
    
    public function __construct() {}
    
    public function draw($coords) {
        $chartWidth = 400;
        $chartWidthMultiplicator = round($chartWidth/count($coords));
        $chartHeight = 200;
        $chartHeightMultiplicator = round($chartHeight/count($coords));
        $str = "<canvas style='border:1px solid black;' width='" . $chartWidth
                . "' height='" . $chartHeight
                . "' id='Canvas'></canvas>";
	$str .= "<script>";
	$str .= "var canvas = document.getElementById('Canvas');";
	$str .= "var ctx = canvas.getContext('2d');";
	$str .= "ctx.moveTo(0, 100);";
	for($i = 0; $i < count($coords) - 1; $i++) {
		$str .= "ctx.lineTo("
                        . ($i * $chartWidthMultiplicator)
                        . ", "
                        . ($chartHeight - $coords[$i] * $chartHeightMultiplicator)
                        . ");";
	}
	$str .= "ctx.stroke();";
	$str .= "</script>";
	
	return $str;
    }
    public function redraw() {}
    
    public function gDraw() {
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"chart_div\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'X');
                      data.addColumn('number', 'Dogs');
                      data.addColumn('number', 'Cats');

                      data.addRows([
                        [0, 0, 0],    [1, 10, 5],   [2, 23, 15],  [3, 17, 9]
                      ]);

                      var options = {
                        hAxis: {
                          title: 'Time',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Popularity',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#a52714', '#097138'],
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawWithTrendline($chartData) {
        
        // Get a groupID which is an innerKey
        // of a 2-dimensional array.
        $groupID = array_shift(array_keys($chartData[0]));
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
            $preparedRows .= "[ " . $counter . ", " . $row[$groupID] . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"chart_div" . $groupID . "\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'Группа " . $groupID . "');

                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#097138'],
                        trendlines: {
                          0: {type: 'exponential', color: '#333', opacity: 1},
                          1: {type: 'linear', color: '#111', opacity: .3}
                        }
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('chart_div" . $groupID . "'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
}