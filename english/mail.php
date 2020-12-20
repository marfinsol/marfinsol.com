<?php

if( $_REQUEST['human'] ) {
	header('Location: error.html' );
}

// Data cleaning function from http://www.digital-web.com/articles/bulletproof_contact_form_with_php/ 
function clean_data($string) {
	if (get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	$headers = array(
    	"/to\:/i",
    	"/from\:/i",
    	"/bcc\:/i",
    	"/cc\:/i",
    	"/Content\-Transfer\-Encoding\:/i",
    	"/Content\-Type\:/i",
    	"/Mime\-Version\:/i" 
  	); 
  	$string = preg_replace($headers, '', $string);
  
	return strip_tags($string);
}

// Customize our script by changing the following 2 variables.
$to = 'info@marfinsol.com';
$subject = 'You have a message from your contact form';
// Get the data sent from the form and store them in variables for use later in the script.
$name = clean_data($_REQUEST['name']);
$from = clean_data($_REQUEST['from']);
$message = clean_data($_REQUEST['message']);

// Construct the email message to send.
$email_message = 'Name: ' . $name;
$email_message .= "\n"; // Add a new line. \n needs to be wrapped around double quotes for this to work properly.
$email_message .= 'From: ' . $from;
$email_message .= "\n\n"; //Two new lines.
$email_message .= $message;

// Specify the From and Reply-To fields of the email.
$headers = 'From: ' . $name . ' <' . $from . '>' . "\r\n" .
    'Reply-To: ' . $from . "\r\n";

$sent = false;
// If there is a message provided then send the email.
if( $message ) {
	$sent = mail( $to , $subject , $email_message, $headers ); // See http://php.net/manual/en/function.mail.php 
}

// If this is an AJAX request then we will just print something out on the screen instead of redirecting to a page. 
if( $_REQUEST['ajax'] == 1 ) {
	if( $sent ) {
		echo 'Message sent!';
	} else {
		echo 'There was an error';
	}
// If this isn't an AJAX request then redirect the user to the appropriate page.
} else {
	if( $sent ) {
		header('Location: sent.html' );
	} else {
		header('Location: error.html' );
	}
}
?>