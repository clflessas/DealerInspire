<?php
if( count(get_included_files()) == 1 ) header("Location: index.html"); //if accessed directly, go to the home page
/*All sanitizing functions here*/
class CleanVars {
		
	public function basic_string_clean($str){
		$str = strip_tags($str);
		$str = str_replace(array('@','*','^','=','(',')','_'),'',$str);
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		return trim($str);
	}
	
	public function basic_numeric_string($num){
		$num = preg_replace('/(?![0-9])*/', '', $num); //strip everything but numerals
		return $num;
	}
	
	public function validate_email($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL); //returns true if email is valid
	}

}
?>