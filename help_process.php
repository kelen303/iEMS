<?php
   // create email  
	$messageString = "The following inquiry has been submitted.\n\n\n";
	$messageString .= "Contact Information\n";
	$messageString .= "-------------------------\n";
	$messageString .= "Name: " .$_POST['firstname'] ." " .$_POST['lastname'] ."\n";
	$messageString .= "Email: " .$_POST['email'] ."\n";
	$messageString .= "Phone: " .$_POST['phone'] ."\n";
	$messageString .= "-------------------------\n";
	$messageString .= "Company: " .$_POST['company'] ."\n";
	$messageString .= "Department: " .$_POST['department'] ."\n";
	$messageString .= "\n\n";
	$messageString .= "Question/Comment/Request\n";
	$messageString .= "-------------------------\n";
	$messageString .= $_POST['comments'] ."\n";
	
	// from (may require local email address)
   $from="webhelp@crsolutions.us";
   // to (change to client contact email address)
   $to= "webhelp@crsolutions.us";
   // subject line of email 
   $subject="Form Inquiry From CRSolutions.us";
   $header="From:" .$from ."\r\n";
   //$header .= "Bcc:jenniday1229@yahoo.com\r\n"; //Bcc for testing purposes
	
	$message = $messageString;
	
	if (@mail($to, $subject, $message, $header)){
		$url = "Location:contactus_thanks.html";
		header($url);

	} else {
		$url = "Location:contactus_error.html";
		header($url);
		}
	

?>