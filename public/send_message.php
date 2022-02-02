<?php
	if( count(get_included_files()) == 1 ) header("Location: index.html"); //if accessed directly, go to the home page
		
	$not_valid = 'Required';//to replace invalid fields
	$has_null_vars = false; //assume all necessary variables are not null
	
	
	//check necessary fields, if not valid, change value and change has_null_vars boolean
	require_once('classes/cleanvars.class.php');
	$check = new CleanVars();
	$required = array();
	
	//check phone number
	$phone = $check->basic_numeric_string($phone);
	if(strlen($phone) != 10 ){
		$phone = ''; //since phone is not required, set to empty string
	}
	
	//check email
	if (! $check->validate_email($email) ) {
		$email = $not_valid;
		$required[]= '#email';
		$has_null_vars = true;
	}
	
	//check name
	$name = $check->basic_string_clean($name);
	if( $name == '' or $name == $not_valid){
		$name = $not_valid;
		$required[] = '#name';
		$has_null_vars = true;
	}
	//check message
	$message = $check->basic_string_clean($message);
	if( $message == '' or $name == $not_valid){
		$message = $not_valid;
		$required[] = '#message';
		$has_null_vars = true;
	}
	
	$required = implode(', ', $required); //implode required variable so it'll read a variation of "#phone, #name, #message"
	
	//only access variables if the necessary variables are not null
	if(! $has_null_vars){	
		//Access database
		include('classes/dbase.class.php');
		$dbase = new DBase();
		$sql = 'insert into contact_info (name, email, message, phone) values (?, ?, ?, ?)';
		$values = array($name, $email, $message, $phone);
		
		$dbase->db_execute($sql, $values, 'ssss'); //since all 4 are strings, parameter variable is 'ssss'
		
		$to = 'guy-smiley@example.com';
		$subject = 'Contact Request for Guy Smiley';
		$e_msg = "From: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
		
		try{
			$success = mail($to, $subject, $e_msg, "From: contact_request@dealer_inspire.com");
		} catch (Exception $e){//if there's an error, set to false
			$success = false;
		}
	}
	
	
	//prepare phone number for output, format to (###) ###-####
	if($phone !== '') $phone = '('. substr($phone, 0, 3) . ') '. substr($phone, 3, 3) . '-' . substr($phone, 6);
?>