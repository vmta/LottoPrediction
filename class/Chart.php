<?php
/**
 * Description of Chart
 * 
 * Define common options for all charts, such as correct ID, width, height,
 * as well as external script download, where needed.
 *
 * @author vmta
 */
class Chart {
    
    /**
     * Default container width
     * @var type integer
     */
    private $defaultContainerWidth = 800;
    
    /**
     * Default container height
     * @var type integer
     */
    private $defaultContainerHeight = 500;
    
    /**
     * Default container ID
     * @var type string
     */
    private $defaultContainerID = "chart_div";
    
    /**
     * Container width
     * @var type integer
     */
    private $containerWidth;
    public function setContainerWidth($containerWidth) {
        $this->containerWidth = $containerWidth;
    }
    public function getContainerWidth() { return $this->containerWidth; }
    
    /**
     * Container height
     * @var type integer
     */
    private $containerHeight;
    public function setContainerHeight($containerHeight) {
        $this->containerHeight = $containerHeight;
    }
    public function getContainerHeight() { return $this->containerHeight; }
    
    /**
     * Container ID
     * @var type stirng
     */
    private $containerID;
    public function setContainerID($containerID) {
        $this->containerID = $containerID;
    }
    public function getContainerID() { return $this->containerID; }
    
    /**
     * Container HMTL
     * @var type string
     */
    private $container;
    public function setContainer($container) { $this->container = $container; }
    public function getContainer() { return $this->container; }
    
    /**
     * An HTML line defining JavaScript to be preloaded in order to provide JS
     * functionality and chart rendering.
     * @var type string
     */
    private $preloadScript = "<script type=\"text/javascript\""
            . "src=\"https://www.gstatic.com/charts/loader.js\"></script>";
    public function getPreloadScript() {
        if($this->isPreloaded()) {
            return "";
        }
        return $this->preloadScript;
    }
    
    /**
     * Status of preloadable script. (In order to prevent multiple download of
     * this script on the multichart page.)
     * @var type boolean
     */
    private $preloadScriptLoaded = FALSE;
    function isPreloaded() {
        if($this->preloadScriptLoaded) {
            return TRUE;
        }
        $this->preloadScriptLoaded = TRUE;
        return FALSE;
    }
    
    /**
     * Initialize Object
     * @param type $containerID
     * @param type $containerWidth
     * @param type $containerHeight
     */
    function __construct($containerID, $containerWidth, $containerHeight) {
        
        /**
         * Initiralize containerID
         */
        if(!isset($containerID) || empty($containerID)) {
            $containerID = $this->defaultContainerID;
        }
        $this->setContainerID($containerID);
        
        /**
         * Initialize containerWidth
         */
        if(!isset($containerWidth) || empty($containerWidth)) {
            $containerWidth = $this->defaultContainerWidth;
        }
        $this->setContainerWidth($containerWidth);
        
        /**
         * Inititalize containerHeight
         */
        if(!isset($containerHeight) || empty($containerHeight)) {
            $containerHeight = $this->defaultContainerHeight;
        }
        $this->setContainerHeight($containerHeight);
        
        /**
         * Initialize container (DIV HTML w. ID, Width, Height)
         */
        $container = "<div id=\"".$this->getContainerID()."\""
                . "style=\"width:".$this->getContainerWidth()."px;"
                . "height:".$this->getContainerHeight()."px;\"></div>";
        $this->setContainer($container);
    }
    
    /**
     * Prepare JavaScript chart variable data in a specific format.
     * @param type $data
     * @return string
     */
    function dataConstructor($data) {
        $cols = ""; // Construct columns for the chart
        $rows = ""; // Construct rows for the chart
        $rIndx = 0;
        foreach($data as $row) {
            if($rIndx == 0) {
                $cols .= "[";
                $cIndx = 0;
                foreach(array_keys($row) as $key) {
                    $cols .= "'".$key."'";
                    if($cIndx < count($row) - 1) {
                        $cols .= ",";
                    }
                    $cIndx++;
                }
                $cols .= "]";
            }
            $rows .= "[";
            $cIndx = 0;
            foreach($row as $cell) {
                $rows .= $cell;
                if($cIndx < count($row) - 1) {
                    $rows .= ",";
                }
                $cIndx++;
            }
            $rows .= "]";
            if($rIndx < count($data) - 1) {
                $rows .= ",";
            }
            $rIndx++;
        }
        return $cols.",".$rows;
    }
    
    /**
     * Prepare JavaScript chart variable options in a specific format.
     * @param type $seriesType
     * @return string
     */
    function optionsConstructor($seriesType) {
        $str = "{
                title : 'Draw Numbers',
                vAxis: {title: 'Numbers'},
                hAxis: {title: 'Draw ID'},
                seriesType: '".$seriesType."',
                curveType: 'function',
                legend: { position: 'right' }
                }";
        return $str;
    }
}