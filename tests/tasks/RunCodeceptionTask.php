<?php
class RunCodeceptionTask extends Task 
{
	protected $type = null;
	
	public function main() 
	{
		$outputAndErrors = "";
		$return_value = "";
		
		exec("vendor/bin/codecept run " . $this->type, $outputAndErrors, $return_value);
		
		print_r(implode("\n", $outputAndErrors));
	}
	
	public function setType($type) 
	{
		$this->type = $type;
	}
}
?>
