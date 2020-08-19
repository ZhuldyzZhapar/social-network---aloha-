<?php
session_start();
$urok = "22";
error_reporting(E_ERROR);
$mails = ['yandex.kz', 'yandex.ru', 'gmail.com', 'yahoo.com', 'mail.ru'];
if(isset($_POST['subject'])){
	$subject = $_POST['subject'];
	if($subject == ''){
		unset($subject);
	}
}
if(isset($_POST['email'])){
	$email = $_POST['email'];
	if(stripos($email, '@')){
		$array = explode('@', $email);
		if(!in_array(end($array), $mails)){ // if new_email is certified mail
			$_SESSION['email_us'] = 'sent';
			$_SESSION['message_email'] = 'Only certified mail is allowed.';
			echo "<script> window.location.replace('email_us.php')</script>";
		}
	}else{
		$_SESSION['email_us'] = 'sent';
		$_SESSION['message_email'] = 'Only certified mail is allowed.';
		echo "<script> window.location.replace('email_us.php')</script>";
	}
	if($email == ''){
		unset($email);
	}
}
if(isset($_POST['comment'])){
	$comment = $_POST['comment'];
	if($comment == ''){
		unset($comment);
	}
}
if(isset($_POST['submit'])){
	$submit	= $_POST['submit'];
	if($submit == ''){
		unset($submit);
	}
}

if (isset($subject) ) {
$subject = stripslashes($subject);
$subject = htmlspecialchars($subject);
}
if (isset($email) ) {
$email = stripslashes($email);
$email = htmlspecialchars($email);
}
if (isset($comment) ) {
$comment = stripslashes($comment);
$comment = htmlspecialchars($comment);
}
// adres pocty kuda pridet message
$address = "gsagimbaeva37@gmail.com";
$note_text = "Theme: $urok \r\n name : $subject \r\n Email : $email \r\n text: $comment";

if (isset($subject)  &&  isset ($submit) ) {
mail($address, $urok, $note_text, "Content-type:text/plain; windows-1251"); 
}
$_SESSION['email_us'] = 'sent';
$_SESSION['subject'] = $subject;
$_SESSION['email'] = $email;
echo "<script> window.location.replace('email_us.php')</script>";
?>