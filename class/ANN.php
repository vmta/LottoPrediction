<?php
/**
 * Description of ANN
 *
 * @author vmta
 */
class ANN {
    
    private $num_layers;
    private $num_input;
    private $num_neurons_hidden;
    private $num_output;
    private $desired_error;
    private $max_epochs;
    private $epochs_between_reports;
    
    private $annConf;
    private $ann;
    private $annReady = false;
    function isReady() { return $this->annReady; }
    private $annTrained = false;
    function isTrained() { return $this->annTrained; }
    
    function __construct() {
        $this->num_layers = (func_num_args() > 0) ? func_get_arg(0) : 3;
        $this->num_input = (func_num_args() > 1) ? func_get_arg(1) : 6;
        $this->num_neurons_hidden = (func_num_args() > 2) ? func_get_arg(2) : 1000;
        $this->num_output = (func_num_args() > 3) ? func_get_arg(3) : 6;
        $this->desired_error = 0.001;
        $this->max_epochs = 500000;
        $this->epochs_between_reports = 1000;
        $this->annConf = dirname(__FILE__) . "/ann.conf";
        $this->annReady = true;
    }
    
    function __destruct() {
        $this->clean();
        $this->annReady = false;
        $this->annTrained = false;
    }
    
    function train($trainDataFile) {
        $this->clean();
        $this->ann = fann_create_standard(
            $this->num_layers,
            $this->num_input,
            $this->num_neurons_hidden,
            $this->num_output
            );
        if($this->ann) {
            fann_set_activation_function_hidden($this->ann, FANN_SIGMOID);
            fann_set_activation_function_output($this->ann, FANN_SIGMOID);
        }
        if(fann_train_on_file(
                $this->ann,
                $trainDataFile,
                $this->max_epochs,
                $this->epochs_between_reports,
                $this->desired_error
                )) {
            if(!fann_save($this->ann, $this->annConf)) {
                die("Artifical Neural Network configuration could not be "
                        . "saved to " . $this->annConf);
            } else {
                $this->annTrained = true;
            }
        } else {
            die("Artificial Neural Network could not train on " . $trainDataFile);
        }
    }
    
    function run($input) {
        $this->clean();
        $this->ann = fann_create_from_file($this->annConf);
        if (!$this->ann) {
            die("Artificial Neural Network could not be created from file.");
        }
        return fann_run($this->ann, $input);
    }
    
    private function clean() {
        if($this->ann) {
            fann_destroy($this->ann);
        }
    }
}