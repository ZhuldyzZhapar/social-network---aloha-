<?php 
session_start();
$user_id = $_SESSION['user_id'];
$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
$stmt = $connect->prepare("SELECT * 
						FROM user_information 
						WHERE user_id != $user_id
						ORDER BY surname ASC 
						LIMIT 4");
$stmt->execute();
$users = $stmt->fetchAll();
$msg1 = "";
// search users

if(isset($_POST['search_user'])){
	global $connect;
	$find_user = addslashes($_POST['users']);
	$params = ["%{$find_user}%"];
	// this code for search user by name or surname 
	$stmt = $connect->prepare("SELECT * FROM user_information WHERE name LIKE :name OR surname LIKE :surname ORDER BY user_id LIMIT 5");
	$stmt->bindParam(':name', $params[0]);
	$stmt->bindParam(':surname', $params[0]); 
	$stmt->execute();
	$users = $stmt->fetchAll();
	if (empty($users)) {
		$msg1 = "Not user like this";
	}
}