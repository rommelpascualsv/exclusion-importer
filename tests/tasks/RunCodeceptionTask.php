<?php
class RunCodeceptionTask extends Task {
	
	protected $type = null;
	protected $env = null;
	
	public function init() {
		
	}
	
	public function main() {
		
		$outputAndErrors;
		$return_value;
		
		exec("php codecept.phar run unit", $outputAndErrors, $return_value);
		
		print_r(implode("\n", $outputAndErrors));
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function setEnv($env) {
		$this->env = $env;
	}
}
?>