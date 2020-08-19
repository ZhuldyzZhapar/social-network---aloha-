<?php
session_start();

$user_id = $_SESSION['user_id'];
$zero = '0';
$one = '1';

if(isset($_GET['cancel_my_request_friend_id'])){// cancel the friend request
	$current_user_id = $_GET['cancel_my_request_friend_id'];
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("DELETE FROM friends WHERE user_id=:user_id AND friend_id=:friend_id;");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->bindParam(':friend_id', $current_user_id);
	$stmt->execute();
	}
	catch(PDOException $e){
	echo "<p>Error: </p>" . $e->getMessage();
	}
	$link = 'user_profile.php?id=' . $current_user_id;
	header ('Location: ' . $link);
  	exit();
}elseif(isset($_GET['accept_friend_id'])){ // make both users friends (both 'request' = 1)
	$current_user_id = $_GET['accept_friend_id'];
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("INSERT INTO friends(user_id, friend_id, request)
								VALUES (:user_id, :friend_id, :request);");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->bindParam(':friend_id', $current_user_id);
	$stmt->bindParam(':request', $one);
	$stmt->execute();
	}
	catch(PDOException $e){
	echo "<p>Error: </p>" . $e->getMessage();
	}
	try{
	$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt1 = $connect1->prepare("UPDATE friends SET request=:request WHERE user_id=:user_id AND friend_id=:friend_id;");
	$stmt1->bindParam(':user_id', $current_user_id);
	$stmt1->bindParam(':friend_id', $user_id);
	$stmt1->bindParam(':request', $one);
	$stmt1->execute();
	}
	catch(PDOException $e){
	echo "<p>Error1: </p>" . $e->getMessage();
	}
	$link = 'user_profile.php?id=' . $current_user_id;
	header ('Location: ' . $link);
  	exit();
}elseif(isset($_GET['reject_friend_id'])){// reject fritend request
	$current_user_id = $_GET['reject_friend_id'];
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("DELETE FROM friends WHERE user_id=:user_id AND friend_id=:friend_id;");
	$stmt->bindParam(':user_id', $current_user_id);
	$stmt->bindParam(':friend_id', $user_id);
	$stmt->execute();
	}
	catch(PDOException $e){
	echo "<p>Error: </p>" . $e->getMessage();
	}
	$link = 'user_profile.php?id=' . $current_user_id;
	header ('Location: ' . $link);
  	exit();
}elseif(isset($_GET['add_friend_id'])){
	$current_user_id = $_GET['add_friend_id'];
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("INSERT INTO friends(user_id, friend_id, request) 
								VALUES (:user_id, :friend_id, :request)");
	$stmt->bindParam(':user_id', $user_id);
	$stmt->bindParam(':friend_id', $current_user_id);
	$stmt->bindParam(':request', $zero);
	$stmt->execute();
	}
	catch(PDOException $e){
	echo "<p>Error: </p>" . $e->getMessage();
	}
	$link = 'user_profile.php?id=' . $current_user_id;
	header ('Location: ' . $link);
  	exit();
}elseif(isset($_GET['delete_friend_id'])){
	$current_user_id = $_GET['delete_friend_id'];
	try{
	$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $connect->prepare("DELETE FROM friends WHERE user_id=:user_id AND friend_id=:friend_id;");
	$stmt->bindParam(':user_id', $current_user_id);
	$stmt->bindParam(':friend_id', $user_id);
	$stmt->execute();
	}
	catch(PDOException $e){
	echo "<p>Error1: </p>" . $e->getMessage();
	}
	$stmt1 = $connect->prepare("DELETE FROM friends WHERE user_id=:user_id AND friend_id=:friend_id;");
	$stmt1->bindParam(':user_id', $user_id);
	$stmt1->bindParam(':friend_id', $current_user_id);
	$stmt1->execute();
	$link = 'user_profile.php?id=' . $current_user_id;
	header ('Location: ' . $link);
  	exit();
}else{
	$chat_id = $_POST['chat_id'];
	$_SESSION['chat_id'] = $chat_id;
	$message = $_POST['message'];
	$if_seen = 'no';
	try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("INSERT INTO messenger (chat_id, user_send, message, if_seen) 
									VALUES (:chat_id, :user_send, :message, :if_seen);");
		$stmt->bindParam(':chat_id', $chat_id);
		$stmt->bindParam(':user_send', $user_id);
		$stmt->bindParam(':message', $message);
		$stmt->bindParam(':if_seen', $if_seen);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p>Error: </p>" . $e->getMessage();
	}
	header ('Location: messages.php');
  	exit();
}
?>