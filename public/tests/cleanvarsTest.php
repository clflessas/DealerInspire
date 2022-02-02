<?php
include('../classes/cleanvars.class.php');

use PHPUnit\Framework\TestCase;

/*
*	I apologize ahead of time. This is my first time writing a PHPUnit test and I was having trouble getting PHPUnit to work with XAMPP.
*/

class CleanVarsTest extends TestCase {
	protected $cv;
	
	protected function setUp():void
	{
		$this->cv = new CleanVars();
	}
	
	protect function tearDown():void
	{
	}
	
	public function clean_basic_string_email_test() //what happens if receiving an email for a basic string clean
	{
		$test = "something@gmail.com";
		$expected = "somethinggmail.com";//strip away special characters
		$result = $this->cv->basic_string_clean($test);
		
		$this->assertEqual($expected, $result);
	}
	
	public function clean_basic_string_script_test() //what happens if receiving a scripting attack
	{
		$test = "<script>alert('attack');</script>";
		$expected = "alert&#39;attack&#39;";//strip away tags and convert some characters into html alt code
		$result = $this->cv->basic_string_clean($test);
		
		$this->assertEqual($expected, $result);
	}
	
	public function clean_basic_numeric_test() //what happens if numeric receives something other than numbers
	{
		$test = "abcdef0123456";
		$expected = "0123456";//remove the letters
		$result = $this->cv->basic_numeric_string($test);
		
		$this->assertEqual($expected, $result);
	}
	
	public function validate_email_test()
	{
		$test = "moshimoshi";
		$expected = "false"; //expected to return false for an invalid email
		$result = $this->cv->validate_email($test);
		
		$this->assertEqual($expected, $result);
	}	
}
?>