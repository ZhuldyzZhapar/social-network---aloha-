<?php

$one = '1';
$zero = '0';

// function that makes array with info about users into array where key is the id of the user
function make_array($data0){
	$array = array();
	for($i=0; $i<count($data0); $i++){ 
		$current_user_id = $data0[$i]['friend_id'];
		try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
		$stmt->bindParam(':user_id', $current_user_id);
		$stmt->execute();
		$data = $stmt->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error: </p>" . $e->getMessage();
		}
		$array[$current_user_id] = $data[0];
	}
	return $array;
}

// function that makes array with info about users into array where key is the id of the user
// special for people who sent friend request to the user because 
function make_array1($data0){
	$array = array();
	for($i=0; $i<count($data0); $i++){ 
		$current_user_id = $data0[$i]['user_id'];
		try{
		$connect = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $connect->prepare("SELECT * FROM user_information WHERE user_id = :user_id;");
		$stmt->bindParam(':user_id', $current_user_id);
		$stmt->execute();
		$data = $stmt->fetchAll();
		}
		catch(PDOException $e) {
			echo "<p>Error: </p>" . $e->getMessage();
		}
		$array[$current_user_id] = $data[0];
	}
	return $array;
}

// getting data about friends' ids of the user
try{
	$connect1 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt1 = $connect1->prepare("SELECT f.user_id, f.friend_id, f.request, u.surname
								FROM friends f
								INNER JOIN user_information u
								ON f.friend_id = u.user_id
								WHERE f.user_id = :user_id AND request = :request
								ORDER BY u.surname ASC;");
	$stmt1->bindParam(':user_id', $user_id);
	$stmt1->bindParam(':request', $one);
	$stmt1->execute();
	$data1 = $stmt1->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error1: </p>" . $e->getMessage();
}
$friends_array = make_array($data1);

// getting data about users' ids to whom the user send a friend request
try{
	$connect2 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt2 = $connect2->prepare("SELECT f.user_id, f.friend_id, f.request, u.surname
								FROM friends f
								INNER JOIN user_information u
								ON f.friend_id = u.user_id
								WHERE f.user_id = :user_id AND request = :request
								ORDER BY u.surname ASC;");
	$stmt2->bindParam(':user_id', $user_id);
	$stmt2->bindParam(':request', $zero);
	$stmt2->execute();
	$data2 = $stmt2->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error2: </p>" . $e->getMessage();
}
$my_requests = make_array($data2);

// getting from table information about users' ids who sent to me friend request
try{
	$connect3 = new PDO("mysql:host=localhost; dbname=aloha", 'root', '');
	$connect3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt3 = $connect3->prepare("SELECT f.user_id, f.friend_id, f.request, u.surname
								FROM friends f
								INNER JOIN user_information u
								ON f.user_id = u.user_id
								WHERE f.friend_id = :friend_id AND request = :request
								ORDER BY u.surname ASC");
	$stmt3->bindParam(':friend_id', $user_id);
	$stmt3->bindParam(':request', $zero);
	$stmt3->execute();
	$data3 = $stmt3->fetchAll();
}
catch(PDOException $e) {
	echo "<p>Error0: </p>" . $e->getMessage();
}
$requests_to_me = make_array1($data3);
?>