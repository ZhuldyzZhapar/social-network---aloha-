<?php 
session_start();
$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// initialize variables
$post_id = $_SESSION['post_id'];
$user_id = $_SESSION['user_id'];;
$text = "";

// this if for display username of post and select all data of $post_id 
$stmt = $connect->prepare('SELECT p.*, u.name, u.surname FROM posts AS p JOIN user_information AS u ON p.user_id = u.user_id WHERE p.post_id=:post_id');
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();
$posts = $stmt->fetchAll(); 

if(isset($_POST['send'])){
	global $connect;
	if (!empty($_POST['message'])){
		$comm =addslashes($_POST['message']);
		$stmt = $connect->prepare("INSERT INTO comment ( user_id, comm, post_id) VALUES ('$user_id', '$comm', '$post_id') ");
		$stmt->execute();
		echo "<script> window.location.replace('post_singlePost.php?post_id=$post_id')</script>";
	}else{
		echo "<script> window.location.replace('post_singlePost.php?post_id=$post_id')</script>";
	}
}

function all_usernames_commented($post_id){
	global $connect;
	$stmt = $connect->prepare("SELECT p.*, u.name, u.surname FROM comment AS p JOIN user_information AS u ON p.user_id = u.user_id WHERE p.post_id='$post_id' ORDER BY p.date DESC");
	$stmt->execute();
	$data = $stmt->fetchAll();
	return $data;
}
?>