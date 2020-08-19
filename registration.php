<?php
session_start();

$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$birthday = $_SESSION['birthday'];
$password = $_SESSION['password'];
$phone_or_email = $_SESSION['phone_or_email'];
$what = $_SESSION['is_email_or_phone'];

if($what === 'phone'){ // if user used phone number
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect ->prepare("INSERT INTO user_information (password, name, surname, birthday, phone) 
									VALUES (:password, :name, :surname, :birthday, :phone);");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':surname', $surname);
		$stmt->bindParam(':birthday', $birthday);
		$stmt->bindParam(':phone', $phone_or_email);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	session_unset();// deleting all the values in SESSION array
	// find the id of new user to set it into the SESSION array
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT user_id FROM user_information
									WHERE phone = :phone;");
		$stmt1->bindParam(':phone', $phone_or_email);
		$stmt1->execute();
		$data1 = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}

}elseif($what === 'email'){ // if user used email
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect ->prepare("INSERT INTO user_information (password, name, surname, birthday, email) 
									VALUES (:password, :name, :surname, :birthday, :email);");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':surname', $surname);
		$stmt->bindParam(':birthday', $birthday);
		$stmt->bindParam(':email', $phone_or_email);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	session_unset();// deleting all the values in SESSION array
	// find the id of new user to set it into the SESSION array
	try{
		$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt1 = $connect1->prepare("SELECT user_id FROM user_information
									WHERE email = :email;");
		$stmt1->bindParam(':email', $phone_or_email);
		$stmt1->execute();
		$data1 = $stmt1->fetchAll();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
}
$profile = 'profile';
$album = 'Profile images';
try{
	$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt2 = $connect2->prepare("INSERT INTO images (user_id, image_name, album) VALUES (:user_id, :image_name, :album);");
	$stmt2->bindParam(':user_id', $data1[0]['user_id']);
	$stmt2->bindParam(':image_name', $profile);
	$stmt2->bindParam(':album', $album);
	$stmt2->execute();
}
catch(PDOException $e) {
	echo "<p>Error2: </p>" . $e->getMessage();
}
$_SESSION['user_id'] = $data1[0]['user_id'];
echo "<script> window.location.replace('my_profile.php')</script>"; 
$connect = NULL;
$connect1 = NULL;
$connect2 = NULL;
?>