<?php
class RunCodeceptionTask extends Task {
	
	protected $type = null;
	
	public function init() {
		
	}
	
	public function main() {
		
		$outputAndErrors;
		$return_value;
		
		exec("php codecept.phar run ".$type, $outputAndErrors, $return_value);
		
		print_r(implode("\n", $outputAndErrors));
	}
	
	public function setType($type) {
		$this->type = $type;
	}
}
?>
