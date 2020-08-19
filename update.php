<?php
session_start();
$user_id = $_SESSION['user_id'];
$message = '';
$is_post_empty = true;
unset($_POST['submit']);
foreach ($_POST as $value) {
	if($value!==''){
		$is_post_empty = false;
	}
}
if($is_post_empty){
	$_SESSION['is_post_empty'] = 'true';
}else{
	$_SESSION['is_post_empty'] = 'false';
}

if(isset($_GET['delete'])){
	$delete1 = $_GET['delete'] . ' = :' . $_GET['delete'];
	$delete2 = ':' . $_GET['delete'];
	$empty_str = NULL;
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("UPDATE user_information SET " . $delete1 . " WHERE user_id = :user_id;");
	$stmt->bindParam($delete2, $empty_str);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	echo "<script> window.location.replace('edit_profile.php')</script>";
}else{
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$mails = ['yandex.kz', 'yandex.ru', 'gmail.com', 'yahoo.com', 'mail.ru'];
	$ok_phone = false;
	$ok_email = false;
	$ok_password = false;
	if($is_post_empty === false){
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$update_array = array();
		// checking if email is valid
		if(trim($email) !== ''){
			if(stripos($email, '@')){
				$array = explode('@', $email);
				if(in_array(end($array), $mails)){ // if new_email is certified mail
					$update_array[] = 'email = :email';
					$ok_email = true;
				}else{
					$_SESSION['message_email'] = 'Only certified mail is allowed';
				}
			}else{
				$_SESSION['message_email'] = 'Only certified mail is allowed';
			}
		}
		// checking if phone is valid
		if (trim($phone) !== ''){ 
			if(strlen($phone) <= 12 and strlen($phone) > 10 and intval(substr($phone, 1, 11)) >= 1000000000){
				$update_array[] = 'phone = :phone';
				$ok_phone = true;
			}else{
				$_SESSION['message_phone'] = 'Only valid phone number is allowed';
			}
		}
		if (trim($password) !== ''){ 
			if(strlen($password) >= 8){
				$update_array[] = 'password = :password';
				$ok_password = true;
			}else{
				$_SESSION['message_password'] = 'Password is too short (at least 8 symbols)';
			}
		}
		if (trim($_POST['name']) !== ''){ $update_array[] = 'name = :name';}
		if (trim($_POST['surname']) !== ''){ $update_array[] = 'surname = :surname';}
		if (trim($_POST['birthday']) !== ''){ $update_array[] = 'birthday = :birthday';}
		if (trim($_POST['gender']) !== ''){ $update_array[] = 'gender = :gender';}
		if (trim($_POST['family_status']) !== ''){ $update_array[] = 'family_status = :family_status';}
		if (trim($_POST['country']) !== ''){ $update_array[] = 'country = :country';}
		if (trim($_POST['city']) !== ''){ $update_array[] = 'city = :city';}
		if (trim($_POST['university']) !== ''){ $update_array[] = 'university = :university';}
		if (trim($_POST['job']) !== ''){ $update_array[] = 'job = :job';}
		if (trim($_POST['about_me']) !== ''){ $update_array[] = 'about_me = :about_me';}
		if (trim($_POST['hobbies']) !== ''){ $update_array[] = 'hobbies = :hobbies';}
		if (trim($_POST['favourite_books']) !== ''){ $update_array[] = 'favourite_books = :favourite_books';}
		if (trim($_POST['favourite_movies']) !== ''){ $update_array[] = 'favourite_movies = :favourite_movies';}

	    if(sizeof($update_array)) {
	    $sql = ("UPDATE user_information SET " . implode(", ", $update_array) . " WHERE user_id = :user_id;");
	    $stmt = $connect->prepare($sql);
	    $stmt->bindParam(":user_id", $user_id);
		if (trim($_POST['name']) !== ''){ $stmt->bindParam(":name", $_POST['name']);}
		if (trim($_POST['surname']) !== ''){ $stmt->bindParam(":surname", $_POST['surname']);}
		if (trim($_POST['birthday']) !== ''){ $stmt->bindParam(":birthday", $_POST['birthday']);}
		if (trim($_POST['gender']) !== ''){ $stmt->bindParam(":gender", $_POST['gender']);}
		if (trim($_POST['family_status']) !== ''){ $stmt->bindParam(":family_status", $_POST['family_status']);}
		if ($ok_password){ $stmt->bindParam(":password", $_POST['password']);}
		if ($ok_email){ $stmt->bindParam(":email", $_POST['email']);}
		if ($ok_phone){ $stmt->bindParam(":phone", $_POST['phone']);}
		if (trim($_POST['country']) !== ''){ $stmt->bindParam(":country", $_POST['country']);}
		if (trim($_POST['city']) !== ''){ $stmt->bindParam(":city", $_POST['city']);}
		if (trim($_POST['university']) !== ''){ $stmt->bindParam(":university", $_POST['university']);}
		if (trim($_POST['job']) !== ''){ $stmt->bindParam(":job", $_POST['job']);}
		if (trim($_POST['about_me']) !== ''){ $stmt->bindParam(":about_me", $_POST['about_me']);}
		if (trim($_POST['hobbies']) !== ''){ $stmt->bindParam(":hobbies", $_POST['hobbies']);}
		if (trim($_POST['favourite_books']) !== ''){ $stmt->bindParam(":favourite_books", $_POST['favourite_books']);}
		if (trim($_POST['favourite_movies']) !== ''){ $stmt->bindParam(":favourite_movies", $_POST['favourite_movies']);}
	    $stmt->execute();

	    $_SESSION['message'] = '<strong>Updating success</strong><br>Profile information was updated';
		}
	}
	catch(PDOException $e) {
	    echo "<p>Error: </p>" . $e->getMessage();
	}
	$connect = NULL;
	$_SESSION['where'] = 'update';
	?> <script>window.location.replace("my_profile.php")</script> <?php
	}else{
	echo "<script> window.location.replace('my_profile.php')</script>";
	}
}
?>